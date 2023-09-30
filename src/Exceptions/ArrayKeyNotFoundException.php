<?php

namespace Sajadsdi\ArrayDotNotation\Exceptions;

/**
 * Exception thrown when a specific array key is not found.
 */
class ArrayKeyNotFoundException extends \Exception
{
    /**
     * Constructor for the exception.
     *
     * @param string $key The key that was not found.
     * @param string $keysPath The path of keys leading to the missing key (optional).
     */
    public function __construct(public string $key, public string $keysPath = '')
    {
        $message = "We can't find '{$this->key}' key" . ($this->keysPath ? " in '{$this->keysPath}' path" : "") . " in the array.";
        parent::__construct($message);
    }
}