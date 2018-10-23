<?php
namespace WebServCo\Api\Exceptions;

final class AuthorizationException extends AbstractSecurityException
{
    const ERROR_CODE = 'authorization_exception';

    public function __construct($message, $code = 401, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
