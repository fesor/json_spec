<?php

namespace JsonSpec\PhpSpec\Matcher;

use JsonSpec\Matcher\MatcherOptions;
use PhpSpec\Exception\Example\FailureException;

class JsonHaveSizeMatcher implements DelayedMatcherInterface
{

    /**
     * @var \JsonSpec\Matcher\JsonHaveSizeMatcher
     */
    private $matcher;

    public function __construct(\JsonSpec\Matcher\JsonHaveSizeMatcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return boolean
     */
    public function supports($name, $subject, array $arguments)
    {
        return in_array($name, array('haveJsonSize'))
            && 1 === count($arguments)
        ;
    }

    /**
     * @return MatcherOptions
     */
    public function promise()
    {
        return $this->matcher->getOptions();
    }

    /**
     * Evaluates positive match.
     *
     * @param  string                                      $name
     * @param  mixed                                       $subject
     * @param  array                                       $arguments
     * @throws \PhpSpec\Exception\Example\FailureException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        if (!$this->matcher->match($subject, $arguments[0])) {
            throw $this->createError(sprintf('Expected JSON value size to be %d', $arguments[0]));
        }
    }

    /**
     * Evaluates negative match.
     *
     * @param  string                                      $name
     * @param  mixed                                       $subject
     * @param  array                                       $arguments
     * @throws \PhpSpec\Exception\Example\FailureException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        if ($this->matcher->match($subject, $arguments[0])) {
            throw $this->createError(sprintf('Expected JSON value size to not be %d', $arguments[0]));
        }
    }

    /**
     * Returns matcher priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return 50;
    }

    private function createError($message)
    {
        $path = $this->matcher->getOptions()->getPath();
        if ($path !== null) {
            $message .= sprintf(' at path \'%s\'', $path);
        }

        return new FailureException($message);
    }

}
