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
    protected function match($subject, $argument, $matcher = null)
    {
        return $this->matcher->haveType($subject, $argument);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual)
    {
        return $this->createError(sprintf('Expected JSON value type to be %s', $expected));
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual)
    {
        return $this->createError(sprintf('Expected JSON value type to not be %s', $expected));
    }

    /**
     * @param $message
     * @return FailureException
     */
    private function createError($message)
    {
        $path = $this->getOptions()->getPath();
        if ($path !== null) {
            $message .= sprintf(' at path \'%s\'', $path);
        }

        return new FailureException($message);
    }

}
