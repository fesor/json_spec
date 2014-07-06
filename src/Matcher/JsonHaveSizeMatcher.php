<?php

namespace JsonSpec\Matcher;

use JsonSpec\Exception\MissingPathException;
use JsonSpec\Helper\JsonHelper;

class JsonHaveSizeMatcher extends Matcher
{

    public function match($json, $size)
    {
        $data = $this->helper->parse($json, $this->options->getPath());

        if (!is_array($data) && !is_object($data)) {
            return false;
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        return $size === count($data);
    }

}