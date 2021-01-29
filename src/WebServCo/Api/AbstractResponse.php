<?php

declare(strict_types=1);

namespace WebServCo\Api;

use WebServCo\Framework\Http\Response;

abstract class AbstractResponse
{
    protected string $endpoint;
    /**
    * @var mixed
    */
    protected $data;

    protected string $method;

    protected Response $response;

    protected int $status;

    public function __construct(string $endpoint, string $method, Response $response)
    {
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->response = $response;
        $this->status = $this->response->getStatus();
        if (!in_array($this->status, [204, 205])) { // In some situations there is no content to process
            $this->data = $this->processResponseData();
        }
    }

    /**
    * @return mixed
    */
    public function getData()
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

    /**
    * @return mixed
    */
    protected function processResponseData()
    {
        $responseContent = $this->response->getContent();
        $contentType = $this->response->getHeaderLine('content-type');
        $parts = explode(';', $contentType);

        switch ($parts[0]) {
            case 'application/json':
            case 'text/json':
                return json_decode($responseContent, true);
            case 'application/x-www-form-urlencoded':
                if (false === strpos($responseContent, '=')) {
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
                throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException(
                    sprintf('Api returned unsupported content type: %s.', (string) $contentType)
                );
        }
    }
}
