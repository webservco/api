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
        $this->verifyAuthorization();
    }

    abstract protected function verifyAuthorization();

    protected function verifySsl()
    {
        $schema = $this->request->getSchema();
        if ('https' != $schema) {
            throw new \WebServCo\Api\Exceptions\SslException('SSL required');
        }
        return true;
    }
}
