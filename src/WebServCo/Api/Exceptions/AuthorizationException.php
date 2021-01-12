<?php
namespace WebServCo\Api\Exceptions;

final class AuthorizationException extends ApiException
{
    const CODE = 401;
    const ERROR_CODE = 'authorization_exception';

    public function __construct(string $message, \Exception $previous = null)
    {
        parent::__construct($message, self::CODE, $previous);
    }
}
