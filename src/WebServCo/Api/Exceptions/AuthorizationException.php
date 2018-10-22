<?php
namespace WebServCo\Api\Exceptions;

final class AuthorizationException extends AbstractSecurityException
{
    public function __construct($message, $code = 401, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
