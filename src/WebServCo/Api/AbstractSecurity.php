<?php
namespace WebServCo\Api;

use \WebServCo\Framework\Exceptions\NotImplementedException;

abstract class AbstractSecurity
{
    protected $allowedMethods;
    protected $supportedContentTypes;
    protected $request;

    public function __construct(\WebServCo\Framework\Interfaces\RequestInterface $request)
    {
        $this->allowedMethods = [];
        $this->supportedContentTypes = [];
        $this->request = $request;
    }

    public function verify()
    {
        $this->verifySsl();
        $this->verifyMethod();
        $this->verifyContentType();
    }

    public function setAllowedMethods(array $allowedMethods)
    {
        $this->allowedMethods = $allowedMethods;
    }

    public function setSupportedContentTypes(array $supportedContentTypes)
    {
        $this->supportedContentTypes = $supportedContentTypes;
    }

    abstract public function verifyAuthorization();

    protected function verifyContentType()
    {
        $clientContentTypes = $this->request->getAcceptContentTypes();
        if (array_key_exists(0, $clientContentTypes)) {
            unset($clientContentTypes[0]); // $q == 0 means, that mime-type isn’t supported!
        }
        if (empty($this->supportedContentTypes)) {
            throw new NotImplementedException('Content type support not implemented');
        }
        $intersection = array_intersect($clientContentTypes, $this->supportedContentTypes);
        if (empty($intersection)) {
            throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException('Unsupported content type');
        }
        return true;
    }

    protected function verifyMethod()
    {
        if (empty($this->allowedMethods)) {
            throw new NotImplementedException('Method not implemented');
        }
        if (!in_array($this->request->getMethod(), $this->allowedMethods)) {
            throw new \WebServCo\Framework\Exceptions\MethodNotAllowedException('Unsupported method');
        }
        return true;
    }

    protected function verifySsl()
    {
        $schema = $this->request->getSchema();
        if ('https' != $schema) {
            throw new \WebServCo\Framework\Exceptions\SslRequiredException('SSL required');
        }
        return true;
    }
}
