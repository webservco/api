<?php declare(strict_types = 1);

namespace WebServCo\Api\Exceptions;

final class AuthorizationException extends ApiException
{

    public const CODE = 401;
    public const ERROR_CODE = 'authorization_exception';

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::CODE, $previous);
    }
}
