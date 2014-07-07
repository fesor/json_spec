<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\MatcherInterface;

class JsonHavePathMatcher implements MatcherInterface
{

    /**
     * @var \JsonSpec\Matcher\JsonHavePathMatcher
     */
    private $matcher;

    public function __construct(\JsonSpec\Matcher\JsonHavePathMatcher $matcher)
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
     * @return Boolean
     */
    public function supports($name, $subject, array $arguments)
    {
        return in_array($name, array('haveJsonPath'));
    }

    /**
     * Evaluates positive match.
     *
     * @param  string           $name
     * @param  mixed            $subject
     * @param  array            $arguments
     * @throws FailureException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        if (!$this->matcher->match($subject, $arguments[0])) {
            throw $this->createError(sprintf('Expected JSON path "%s"', $arguments[0]));
        }
    }

    /**
     * Evaluates negative match.
     *
     * @param  string           $name
     * @param  mixed            $subject
     * @param  array            $arguments
     * @throws FailureException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        if ($this->matcher->match($subject, $arguments[0])) {
            throw $this->createError(sprintf('Expected no JSON path "%s"', $arguments[0]));
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
        return new FailureException($message);
    }

}
