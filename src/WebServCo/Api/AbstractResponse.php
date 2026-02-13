<?php

declare(strict_types=1);

namespace WebServCo\Api;

use WebServCo\Framework\Exceptions\UnsupportedMediaTypeException;
use WebServCo\Framework\Http\Response;

use function explode;
use function in_array;
use function json_decode;
use function parse_str;
use function sprintf;
use function strpos;

abstract class AbstractResponse
{
    protected mixed $data;

    protected int $status;

    public function __construct(protected string $endpoint, protected string $method, protected Response $response)
    {
        $this->status = $this->response->getStatus();
        // In some situations there is no content to process
        if (in_array($this->status, [204, 205], true)) {
            return;
        }
        $this->data = $this->processResponseData();
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    protected function processResponseData(): mixed
    {
        $responseContent = $this->response->getContent();
        $contentType = $this->response->getHeaderLine('content-type');
        $parts = explode(';', $contentType);

        switch ($parts[0]) {
            case 'application/json':
            case 'text/json':
                return json_decode($responseContent, true);
            case 'application/x-www-form-urlencoded':
                if (strpos($responseContent, '=') === false) {
                    /* Sometimes Discogs returns text/plain with this content type ... */
                    return $responseContent;
                }
                $data = [];
                parse_str($responseContent, $data);

                return $data;
            case 'text/plain':
            case 'text/html':
                return $responseContent;
            default:
                throw new UnsupportedMediaTypeException(
                    sprintf('Api returned unsupported content type: %s.', (string) $contentType),
                );
        }
    }
}
