<?php

declare(strict_types=1);

namespace WebServCo\Api\Exceptions;

use Throwable;
use WebServCo\Framework\Exceptions\HttpException;

// @phpcs:ignore SlevomatCodingStandard.Classes.RequireAbstractOrFinal.ClassNeitherAbstractNorFinal
class ApiException extends HttpException
{
    public const int CODE = 400;

    public function __construct(string $message, int $code = self::CODE, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
