<?php

namespace JsonSpec\Matcher;

class BeJsonEqualMatcher extends Matcher
{

    public function match($actual, $expected)
    {
        $actual = $this->scrub($actual, $this->options->getPath());
        $expected = $this->scrub($expected);

        return $actual === $expected;
    }

}
