<?php
namespace WebServCo\Api;

abstract class AbstractResponse
{
    protected $endpoint;
    protected $data;
    protected $method;
    protected $response; // \WebServCo\Framework\Http\Response
    protected $status;

    public function __construct($endpoint, $method, \WebServCo\Framework\Http\Response $response)
    {
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->response = $response;
        $this->status = $this->response->getStatus();
        if (!in_array($this->status, [204, 205])) { // In some situations there is no content to process
            $this->data = $this->processResponseData();
        }

    }

    public function getData()
    {
        return $this->data;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getStatus()
    {
        return $this->status;
    }

    protected function processResponseData()
    {
        $responseContent = $this->response->getContent();
        $contentType = $this->response->getHeader('content-type');
        $parts = explode(';', (string) $contentType);

        switch ($parts[0]) {
            case 'application/json':
            case 'text/json':
                return json_decode($responseContent, true);
                break;
            case 'application/x-www-form-urlencoded':
                if (false === strpos($responseContent, '=')) {
                    /* Sometimes Discogs returns text/plain with this content type ... */
                    return $responseContent;
                }
                $data = [];
                parse_str($responseContent, $data);
                return $data;
                break;
            case 'text/plain':
            case 'text/html':
                return $responseContent;
                break;
            default:
                throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException(
                    sprintf('Api returned unsupported content type: %s.', (string) $contentType)
                );
                break;
        }
    }
}
