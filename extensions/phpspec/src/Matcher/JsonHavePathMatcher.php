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
    protected function match($subject, $argument, array $options, $matcher = null)
    {
        return $this->matcher->havePath($subject, $argument, $options);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual, array $options)
    {
        return $this->createError(sprintf('Expected JSON path "%s"', $expected), $options);
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual, array $options)
    {
        return $this->createError(sprintf('Expected no JSON path "%s"', $expected), $options);
    }

    /**
     * @param string $message
     * @param array $options
     * @return FailureException
     */
    protected function createError($message, array $options)
    {
        return new FailureException($message);
    }

}
