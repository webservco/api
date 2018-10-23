<?php
namespace WebServCo\Api;

abstract class AbstractSecurity
{
    protected $request;

    public function __construct(\WebServCo\Framework\Interfaces\RequestInterface $request)
    {
        $this->request = $request;
    }

    public function verify()
    {
        $this->verifySsl();
        $this->verifyMethod();
        $this->verifyContentType();
    }

    abstract protected function getSupportedContentTypes();

    abstract protected function getAllowedMethods();

    abstract public function verifyAuthorization();

    protected function verifyContentType()
    {
        $clientContentTypes = $this->request->getAcceptContentTypes();
        if (array_key_exists(0, $clientContentTypes)) {
            unset($clientContentTypes[0]); // $q == 0 means, that mime-type isn’t supported!
        }
        $supportedContentTypes = $this->getSupportedContentTypes();
        $intersection = array_intersect($clientContentTypes, $supportedContentTypes);
        if (empty($intersection)) {
            throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException('Unsupported content type');
        }
        return true;
    }

    protected function verifyMethod()
    {
        if (!in_array($this->request->getMethod(), $this->getAllowedMethods())) {
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
