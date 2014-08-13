<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;

class JsonHaveSizeMatcher extends JsonSpecMatcher implements DelayedMatcherInterface
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
    protected function match($subject, $argument)
    {
        return $this->matcher->haveSize($subject, $argument);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual)
    {
        $this->createError(sprintf('Expected JSON value size to be %d', $expected));
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual)
    {
        $this->createError(sprintf('Expected JSON value size to not be %d', $expected));
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
