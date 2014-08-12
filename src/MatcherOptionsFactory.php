<?php

namespace JsonSpec;

use JsonSpec\MatcherOptions;

class MatcherOptionsFactory
{

    private $excludedKeys;

    /**
     * @param array $excludedKeys
     */
    public function __construct(array $excludedKeys = array())
    {
        $this->excludedKeys = $excludedKeys;
    }

    /**
     * @return MatcherOptions
     */
    public function createOptions()
    {
        return new MatcherOptions($this->excludedKeys);
    }

}
