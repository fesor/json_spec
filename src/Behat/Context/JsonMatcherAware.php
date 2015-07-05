<?php

namespace JsonSpec\Behat\Context;

use Fesor\JsonMatcher\JsonMatcherFactory;

interface JsonMatcherAware
{

    /**
     * @param JsonMatcherFactory $jsonMatcherFactory
     */
    public function setJsonMatcher(JsonMatcherFactory $jsonMatcherFactory);

}
