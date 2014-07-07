<?php

namespace JsonSpec\Matcher;

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
