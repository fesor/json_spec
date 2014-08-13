<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;

class JsonIncludesMatcher extends JsonSpecMatcher
{

    /**
     * @return array of names
     */
    protected function getSupportedNames()
    {
        return array('includeJson');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument)
    {
        return $this->matcher->includes($subject, $argument);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual)
    {
        return $this->createError('Expected included JSON');
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual)
    {
        return $this->createError('Expected excluded JSON');
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
