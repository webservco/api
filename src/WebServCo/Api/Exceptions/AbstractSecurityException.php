<?php
namespace WebServCo\Api\Exceptions;

abstract class AbstractSecurityException extends ApiException
{
    public function __construct($message, $code = 401, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
