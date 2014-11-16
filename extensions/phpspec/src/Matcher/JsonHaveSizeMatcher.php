<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;

class JsonHaveSizeMatcher extends JsonSpecMatcher
{

    /**
     * @inheritdoc
     */
    protected function getSupportedNames()
    {
        return array('haveJsonSize');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument, array $options, $matcher = null)
    {
        return $this->matcher->haveSize($subject, $argument, $options);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual, array $options)
    {
        return $this->createError(sprintf('Expected JSON value size to be %d', $expected), $options);
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual, array $options)
    {
        return $this->createError(sprintf('Expected JSON value size to not be %d', $expected), $options);
    }

}
