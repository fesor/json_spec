<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;

class JsonHavePathMatcher extends JsonSpecMatcher
{

    /**
     * @inheritdoc
     */
    protected function getSupportedNames()
    {
        return array('haveJsonPath');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument, $matcher = null)
    {
        return $this->matcher->havePath($subject, $argument);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual)
    {
        return $this->createError(sprintf('Expected JSON path "%s"', $expected));
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual)
    {
        return $this->createError(sprintf('Expected no JSON path "%s"', $expected));
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
