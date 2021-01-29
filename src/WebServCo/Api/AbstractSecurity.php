<?php
namespace WebServCo\Api;

use WebServCo\Framework\Exceptions\NotImplementedException;
use WebServCo\Framework\Exceptions\Validation\RequiredArgumentException;
use WebServCo\Framework\Interfaces\RequestInterface;

abstract class AbstractSecurity
{
    /**
    * @var array<int,string>
    */
    protected array $allowedMethods;

    /**
    * @var array<int|string,string>
    */
    protected array $clientContentTypes;

    /**
    * @var array<int|string,string>
    */
    protected array $supportedContentTypes;

    protected RequestInterface $request;

    abstract public function verifyAuthorization(): bool;

    public function __construct(RequestInterface $request)
    {
        $this->allowedMethods = [];
        $this->supportedContentTypes = [];
        $this->request = $request;

        $this->clientContentTypes = $this->request->getAcceptContentTypes();
        if (is_array($this->clientContentTypes) && array_key_exists(0, $this->clientContentTypes)) {
            unset($this->clientContentTypes[0]); // $q == 0 means, that mime-type isn’t supported!
        }
    }

    public function verify(): bool
    {
        $this->verifySsl();
        $this->verifyMethod();
        $this->verifyContentType();
        return true;
    }

    /**
    * @return array<int|string,string>
    */
    public function getClientContentTypes(): array
    {
        return $this->clientContentTypes;
    }

    /**
    * @param array<int,string> $allowedMethods
    * @return bool
    */
    public function setAllowedMethods(array $allowedMethods): bool
    {
        $this->allowedMethods = $allowedMethods;
        return true;
    }

    /**
    * @param array<int|string,string> $supportedContentTypes
    * @return bool
    */
    public function setSupportedContentTypes(array $supportedContentTypes): bool
    {
        $this->supportedContentTypes = $supportedContentTypes;
        return true;
    }

    protected function verifyContentType(): bool
    {
        if (empty($this->supportedContentTypes)) {
            throw new RequiredArgumentException('Missing supported content types');
        }
        // 21.04.2020 seems this is never reached, if client sends empty accept header, it is set to "*/*"
        if (empty($this->clientContentTypes)) {
            throw new RequiredArgumentException('Missing client content type');
        }
        $intersection = array_intersect($this->clientContentTypes, $this->supportedContentTypes);
        if (empty($intersection)) {
            throw new \WebServCo\Framework\Exceptions\UnsupportedMediaTypeException('Unsupported content type');
        }
        return true;
    }

    protected function verifyMethod(): bool
    {
        if (empty($this->allowedMethods)) {
            throw new NotImplementedException('Method not implemented');
        }
        if (!in_array($this->request->getMethod(), $this->allowedMethods)) {
            throw new \WebServCo\Framework\Exceptions\MethodNotAllowedException('Unsupported method');
        }
        return true;
    }

    protected function verifySsl(): bool
    {
        $schema = $this->request->getSchema();
        if ('https' != $schema) {
            throw new \WebServCo\Framework\Exceptions\SslRequiredException('SSL required');
        }
        return true;
    }
}
