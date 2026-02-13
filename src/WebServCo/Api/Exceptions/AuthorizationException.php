<?php

declare(strict_types=1);

namespace WebServCo\Api\Exceptions;

use Throwable;

final class AuthorizationException extends ApiException
{
    public const int CODE = 401;
    public const string ERROR_CODE = 'authorization_exception';

    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, self::CODE, $previous);
    }
}
