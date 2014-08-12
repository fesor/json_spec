<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;

class BeJsonEqualMatcher extends JsonSpecMatcher
{

    /**
     * @inheritdoc
     */
    protected function getSupportedNames()
    {
        return array('beJsonEqual');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument)
    {
        return $this->matcher->isEqual($subject, $argument);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual)
    {
        return $this->createError('Expected equivalent JSON');
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual)
    {
        return $this->createError('Expected inequivalent JSON');
    }

    /**
     * @param $message
     * @return FailureException
     */
    private function createError($message)
    {
        return new FailureException($message);
    }

}
