<?php
namespace WebServCo\Api\Exceptions;

final class SslException extends AbstractSecurityException
{
    public function __construct($message, $code = 400, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
