<?php

declare(strict_types=1);

namespace WebServCo\Api;

use WebServCo\Framework\Http\Response;

abstract class AbstractResponse
{
    protected string $endpoint;

    protected mixed $data;

    protected string $method;

    protected Response $response;

    protected int $status;

    public function __construct(string $endpoint, string $method, Response $response)
    {
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->response = $response;
        $this->status = $this->response->getStatus();
        // In some situations there is no content to process
        if (\in_array($this->status, [204, 205], true)) {
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
        $parts = \explode(';', $contentType);

        switch ($parts[0]) {
            case 'application/json':
            case 'text/json':
                return \json_decode($responseContent, true);
            case 'application/x-www-form-urlencoded':
                if (false === \strpos($responseContent, '=')) {
                    /* Sometimes Discogs returns text/plain with this content type ... */
                    return $responseContent;
                }
                $data = [];
                \parse_str($responseContent, $data);
                return $data;
            case 'text/plain':
            case 'text/html':
                return $responseContent;
            default:
                throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException(
                    \sprintf('Api returned unsupported content type: %s.', (string) $contentType),
                );
        }
    }
}
