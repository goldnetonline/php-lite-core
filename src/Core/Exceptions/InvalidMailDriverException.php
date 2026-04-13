<?php
namespace Goldnetonline\PhpLiteCore\Exceptions;

class InvalidMailDriverException extends \Exception
{

    public function __construct(string $driver)
    {
        $this->message = "Mail Driver $driver not supported";
    }
}
