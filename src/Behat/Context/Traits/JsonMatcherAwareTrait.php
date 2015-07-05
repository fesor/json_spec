<?php

namespace JsonSpec\Behat\Context\Traits;

use Fesor\JsonMatcher\JsonMatcherFactory;

trait JsonMatcherAwareTrait
{

    /**
     * @var JsonMatcherFactory
     */
    private $matcherFactory;

    /**
     * @param JsonMatcherFactory $matcherFactory
     */
    public function setJsonMatcher(JsonMatcherFactory $matcherFactory)
    {
        $this->matcherFactory = $matcherFactory;
    }

    /**
     * @param $json
     * @return \Fesor\JsonMatcher\JsonMatcher
     */
    protected function json($json)
    {
        return $this->matcherFactory->create($json);
    }

}
