<?php

declare(strict_types=1);

namespace WebServCo\Api;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Api\Exceptions\ApiException;
use WebServCo\Framework\Interfaces\RequestInterface;

abstract class AbstractClientRequest
{
    protected bool $allowMultipleDataObjects;
    protected RequestInterface $request;
    protected bool $processRequestData;
    /**
    * @var array<mixed>
    */
    protected array $requestData;

    const MSG_TPL_INVALID = 'Invalid data: %s';
    const MSG_TPL_MAXIMUM_LENGTH = 'Maximum length exceeded: %s: %s';
    const MSG_TPL_REQUIRED = 'Missing required data: %s';

    public function __construct(RequestInterface $request)
    {
        $this->allowMultipleDataObjects = false;
        $this->request = $request;
        $requestMethod = $this->request->getMethod();
        if (in_array($requestMethod, [\WebServCo\Framework\Http\Method::POST])) {
            $this->processRequestData = true;
            $this->requestData = json_decode($this->request->getBody(), true);
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
        if ($parts[0] != Document::CONTENT_TYPE) {
            throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException(
                sprintf('Unsupported content type: %s.', (string) $contentType)
            );
        }
        return true;
    }

    protected function verifyRequestData(): bool
    {
        if (!is_array($this->requestData)) {
            $this->throwInvalidException('root object');
        }
        foreach (['jsonapi', 'data'] as $item) {
            if (!isset($this->requestData[$item])) {
                $this->throwRequiredException($item);
            }
        }
        if (!isset($this->requestData['jsonapi']['version'])) {
            $this->throwRequiredException('jsonapi.version');
        }
        if ($this->requestData['jsonapi']['version'] != Document::VERSION) {
            throw new ApiException(
                sprintf('Unsupported JSON API version: %s', $this->requestData['jsonapi']['version'])
            );
        }
        if (!is_array($this->requestData['data'])) {
            $this->throwInvalidException('data');
        }
        $key = key($this->requestData['data']);
        if (0 === $key) { //multiple data objects
            if (!$this->allowMultipleDataObjects) {
                throw new ApiException('Multiple data objects not allowed for this endpoint');
            }
            foreach ($this->requestData['data'] as $item) {
                $this->verifyData($item);
            }
        } else { // single data object
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
            if (!isset($data[$item])) {
                $this->throwRequiredException(sprintf('data.%s', $item));
            }
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
        if (isset($this->requestData['meta'])) { // meta is optional
            if (!is_array($this->requestData['meta'])) {
                $this->throwInvalidException('meta');
            }
        }
        return true;
    }
}
