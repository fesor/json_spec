<?php

namespace JsonSpec\Exception;

class MissingDirectoryException extends JsonSpecException
{
    public function __construct()
    {
        $this->message = sprintf('Directory not defined');
    }
}
