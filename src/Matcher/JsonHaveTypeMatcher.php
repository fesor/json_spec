<?php

namespace JsonSpec\Matcher;

use JsonSpec\Exception\MissingPathException;
use JsonSpec\Helper\JsonHelper;

class JsonHaveTypeMatcher extends Matcher
{

    public function match($json, $type)
    {
        $data = $this->helper->parse($json, $this->options->getPath());

        if ($type == 'float') {
            $type = 'double';
        }

        return gettype($data) === $type;
    }
}