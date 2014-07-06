<?php

namespace JsonSpec\PhpSpec\Matcher;

use JsonSpec\Matcher\MatcherOptions;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\PresenterInterface;

class BeJsonEqualMatcher implements DelayedMatcherInterface
{

    /**
     * @var \JsonSpec\Matcher\BeJsonEqualMatcher
     */
    private $matcher;

    /**
     * @var PresenterInterface
     */
    private $stringPresenter;


    public function __construct(\JsonSpec\Matcher\BeJsonEqualMatcher $matcher)
    {
        $this->matcher = $matcher;
    }


    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @return boolean
     */
    public function supports($name, $subject, array $arguments)
    {
        return in_array($name, array('beJsonEqual'))
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
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        if (!$this->matcher->match($subject, $arguments[0])) {
            throw $this->createError('Expected equivalent JSON');
        }
    }

    /**
     * Evaluates negative match.
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        if ($this->matcher->match($subject, $arguments[0])) {
            throw $this->createError('Expected inequivalent JSON');
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
