<?php

declare(strict_types=1);

namespace WebServCo\Api;

use InvalidArgumentException;
use Throwable;
use WebServCo\Api\Exceptions\ApiException;
use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Exceptions\UnsupportedMediaTypeException;
use WebServCo\Framework\Http\Method;
use WebServCo\Framework\Interfaces\RequestInterface;

use function explode;
use function is_array;
use function json_decode;
use function key;
use function sprintf;

use const JSON_THROW_ON_ERROR;

abstract class AbstractClientRequest
{
    // No double quotes (API clients would add backslashes)
    public const string MSG_TPL_INVALID = 'Invalid data: \'%s\'.';
    public const string MSG_TPL_MAXIMUM_LENGTH = 'Maximum length exceeded: \'%s\'. Limit: %s';
    public const string MSG_TPL_REQUIRED = 'Missing required data: \'%s\'.';

    protected bool $allowMultipleDataObjects;
    protected bool $processRequestData;

    /**
    * Request data.
    *
    * @var array<mixed>
    */
    protected array $requestData;

    public function __construct(protected RequestInterface $request)
    {
        $this->allowMultipleDataObjects = false;
        $requestMethod = $this->request->getMethod();

        if ($requestMethod !== Method::POST) {
            return;
        }
        $this->processRequestData = true;
        $requestBody = $this->request->getBody();
        // No problem if misisng, set to empty array.
        if (!$requestBody) {
            $this->requestData = [];
        } else {
            // If not missing, it needs to be valid.
            try {
                /**
                * @throws \JsonException
                */
                $requestData = json_decode(
                    $requestBody,
                    // associative
                    true,
                    // depth
                    512,
                    // flags
                    JSON_THROW_ON_ERROR,
                );
                if (!is_array($requestData)) {
                    throw new InvalidArgumentException('Invalid root object.');
                }
                $this->requestData = $requestData;
            } catch (Throwable) {
            // including \JsonException
                $this->throwInvalidException('root object');
            }
        }
    }

    protected function throwInvalidException(string $item): void
    {
        throw new ApiException(sprintf(self::MSG_TPL_INVALID, $item));
    }

    protected function throwMaximumLengthException(string $item, int $maximumLength): void
    {
        throw new ApiException(sprintf(self::MSG_TPL_MAXIMUM_LENGTH, $item, $maximumLength));
    }

    protected function throwRequiredException(string $item): void
    {
        throw new ApiException(sprintf(self::MSG_TPL_REQUIRED, $item));
    }

    protected function verify(): bool
    {
        $this->verifyContentType();
        if ($this->processRequestData) {
            $this->verifyRequestData();
        }

        return true;
    }

    protected function verifyContentType(): bool
    {
        $contentType = $this->request->getContentType();
        $parts = explode(';', (string) $contentType);
        if ($parts[0] !== Document::CONTENT_TYPE) {
            throw new UnsupportedMediaTypeException(
                sprintf('Unsupported request content type: %s.', (string) $contentType),
            );
        }

        return true;
    }

    protected function verifyRequestData(): bool
    {
        if (!is_array($this->requestData)) {
            $this->throwInvalidException('root object');
        }
        // check if empty array, could also mean the json vas invalid
        if (!$this->requestData) {
            $this->throwRequiredException('root object');
        }
        foreach (['jsonapi', 'data'] as $item) {
            if (isset($this->requestData[$item])) {
                continue;
            }

            $this->throwRequiredException($item);
        }
        if (!isset($this->requestData['jsonapi']['version'])) {
            $this->throwRequiredException('jsonapi.version');
        }
        if ($this->requestData['jsonapi']['version'] !== Document::VERSION) {
            throw new ApiException(
                sprintf('Unsupported JSON API version: %s', $this->requestData['jsonapi']['version']),
            );
        }
        if (!is_array($this->requestData['data'])) {
            $this->throwInvalidException('data');
        }
        $key = key($this->requestData['data']);
        //multiple data objects
        if ($key === 0) {
            if (!$this->allowMultipleDataObjects) {
                throw new ApiException('Multiple data objects not allowed for this endpoint');
            }
            foreach ($this->requestData['data'] as $item) {
                $this->verifyData($item);
            }
        } else {
            // single data object
            $this->verifyData($this->requestData['data']);
        }
        $this->verifyMeta();

        return true;
    }

    /**
    * @param array<string,mixed> $data
    */
    protected function verifyData(array $data): bool
    {
        foreach (['type', 'attributes'] as $item) {
            if (isset($data[$item])) {
                continue;
            }

            $this->throwRequiredException(sprintf('data.%s', $item));
        }
        if (empty($data['type'])) {
            $this->throwRequiredException('data.type');
        }
        if (!is_array($data['attributes'])) {
            $this->throwInvalidException('data.attributes');
        }

        return true;
    }

    protected function verifyMeta(): bool
    {
        // meta is optional
        if (isset($this->requestData['meta'])) {
            if (!is_array($this->requestData['meta'])) {
                $this->throwInvalidException('meta');
            }
        }

        return true;
    }
}
