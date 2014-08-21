<?php

namespace JsonSpec\Exception;

class MissingFileException extends JsonSpecException
{
    public function __construct($path)
    {
        $this->message = sprintf('File `%s` is not exists', $path);
    }
}
