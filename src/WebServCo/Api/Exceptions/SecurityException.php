<?php
namespace WebServCo\Api\Exceptions;

final class SecurityException extends ApiException
{
    public function __construct($message, $code = 401, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return sprintf("%s: [%s]: %s\n", __CLASS__, $this->code, $this->message);
    }
}
