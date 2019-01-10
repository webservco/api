<?php
namespace WebServCo\Api;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Exceptions\HttpException;

abstract class AbstractClientRequest
{
    protected $allowMultipleDataObjects;
    protected $request;
    protected $processRequestData;
    protected $requestData;

    const MSG_TPL_INVALID = 'Invalid data: %s';
    const MSG_TPL_REQUIRED = 'Missing required data: %s';

    public function __construct(
        \WebServCo\Framework\Interfaces\RequestInterface $request
    ) {
        $this->allowMultipleDataObjects = false;
        $this->request = $request;
        $requestMethod = $this->request->getMethod();
        if (in_array($requestMethod, [\WebServCo\Framework\Http\Method::POST])) {
            $this->processRequestData = true;
            $this->requestData = json_decode($this->request->getBody(), true);
        }
    }

    protected function verify()
    {
        $this->verifyContentType();
        if ($this->processRequestData) {
            $this->verifyRequestData();
        }
        return true;
    }

    protected function verifyContentType()
    {
        $contentType = $this->request->getContentType();
        $parts = explode(';', $contentType);
        if ($parts[0] != Document::CONTENT_TYPE) {
            throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException('Unsupported content type');
        }
        return true;
    }

    protected function verifyRequestData()
    {
        if (!is_array($this->requestData)) {
            throw new HttpException(sprintf(self::MSG_TPL_INVALID, 'root object'));
        }
        foreach (['jsonapi', 'data'] as $item) {
            if (!isset($this->requestData[$item])) {
                throw new HttpException(sprintf(self::MSG_TPL_REQUIRED, $item));
            }
        }
        if (!isset($this->requestData['jsonapi']['version'])) {
            throw new HttpException(sprintf(self::MSG_TPL_REQUIRED, 'jsonapi.version'));
        }
        if ($this->requestData['jsonapi']['version'] != Document::VERSION) {
            throw new HttpException(
                sprintf('Unsupported JSON API version: %s', $this->requestData['jsonapi']['version'])
            );
        }
        if (!is_array($this->requestData['data'])) {
            throw new HttpException(sprintf(self::MSG_TPL_INVALID, 'data'));
        }
        $key = key($this->requestData['data']);
        if (0 === $key) { //multiple data objects
            if (!$this->allowMultipleDataObjects) {
                throw new HttpException('Multiple data objects not allowed for this endpoint');
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

    protected function verifyData($data)
    {
        foreach (['type', 'attributes'] as $item) {
            if (!isset($data[$item])) {
                throw new HttpException(sprintf(self::MSG_TPL_REQUIRED, sprintf('data.%s', $item)));
            }
        }
        if (empty($data['type'])) {
            throw new HttpException(sprintf(self::MSG_TPL_REQUIRED, 'data.type'));
        }
        if (!is_array($data['attributes'])) {
            throw new HttpException(sprintf(self::MSG_TPL_INVALID, 'data.attributes'));
        }

        return true;
    }

    protected function verifyMeta()
    {
        if (isset($this->requestData['meta'])) { // meta is optional
            if (!is_array($this->requestData['meta'])) {
                throw new HttpException(sprintf(self::MSG_TPL_INVALID, 'meta'));
            }
        }
    }
}
