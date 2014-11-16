<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;

class JsonHaveTypeMatcher extends JsonSpecMatcher
{

    /**
     * @inheritdoc
     */
    protected function getSupportedNames()
    {
        return array('haveJsonType');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument, array $options, $matcher = null)
    {
        return $this->matcher->haveType($subject, $argument, $options);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual, array $options)
    {
        return $this->createError(sprintf('Expected JSON value type to be %s', $expected), $options);
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual, array $options)
    {
        return $this->createError(sprintf('Expected JSON value type to not be %s', $expected), $options);
    }

}
