<?php

namespace JsonSpec\Exception;

class NotIncludedException extends JsonSpecException
{

    public function __construct($expected, $path = null)
    {
        if (!$path) {
            $this->message = sprintf("JSON doesn't includes given part:\n%s", $expected);
        } else {
            $this->message = sprintf("JSON doesn't includes given part at path '%s':\n%s", $path, $expected);
        }
    }

}