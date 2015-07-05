<?php

namespace JsonSpec\Behat\Context\ContextResolver;

use Behat\Behat\Context\ContextClass\ClassResolver;

class JsonSpecContextResolver implements ClassResolver
{
    /**
     * @inheritdoc
     */
    public function supportsClass($contextString)
    {
        return 'json_spec' === $contextString;
    }

    /**
     * @inheritdoc
     */
    public function resolveClass($contextClass)
    {
        return 'JsonSpec\\Behat\\Context\\JsonSpecContext';
    }


}
