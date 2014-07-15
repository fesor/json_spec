<?php

namespace JsonSpec\Matcher;

class BeJsonEqualMatcher extends Matcher
{

    public function match($json, $expected)
    {
        $actual = $this->scrub($json, $this->options->getPath());
        $expected = $this->scrub($expected);

        return $actual === $expected;
    }

}
