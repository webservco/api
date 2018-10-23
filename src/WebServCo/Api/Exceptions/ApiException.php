<?php
namespace WebServCo\Api\Exceptions;

class ApiException extends \WebServCo\Framework\Exceptions\HttpException
{
    const CODE = 400;

    public function __construct($message, $code = self::CODE, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
