<?php

namespace JsonSpec\Exception;

class MissingPathException extends JsonSpecException
{

    public function __construct($path)
    {
        $this->message = sprintf('Path `%s` is not exists for given JSON', $path);
    }

}