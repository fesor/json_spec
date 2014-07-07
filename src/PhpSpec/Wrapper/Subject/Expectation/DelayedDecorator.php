<?php

namespace JsonSpec\PhpSpec\Wrapper\Subject\Expectation;

use JsonSpec\PhpSpec\Matcher\DelayedMatcherInterface;
use PhpSpec\Matcher\MatcherInterface;
use PhpSpec\Wrapper\Subject\Expectation\ExpectationInterface;

class DelayedDecorator implements ExpectationInterface
{

    /**
     * @var MatcherInterface
     */
    private $matcher;

    /**
     * @var ExpectationInterface
     */
    private $expectation;

    /**
     * @var DelayedExpectationManager
     */
    private $delayedExpectations;

    private $alias;

    private $subject;

    private $arguments;

    /**
     * @param ExpectationInterface      $expectation
     * @param MatcherInterface          $matcher
     * @param DelayedExpectationManager $manager
     */
    public function __construct(ExpectationInterface $expectation, MatcherInterface $matcher, DelayedExpectationManager $manager)
    {
        $this->expectation = $expectation;
        $this->matcher = $matcher;
        $this->delayedExpectations = $manager;
    }

    /**
     * @param string $alias
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return mixed
     */
    public function match($alias, $subject, array $arguments = array())
    {
        $this->delayedExpectations->invoke();
        if ($this->matcher instanceof DelayedMatcherInterface) {
            // save arguments for delayed invocation
            $this->alias = $alias;
            $this->subject = $subject;
            $this->arguments = $arguments;

            // register delayed expectation
            $this->delayedExpectations->add($this);

            // return promise
            return $this->matcher->promise();
        }

        return $this->expectation->match($alias, $subject, $arguments);
    }

    public function delayedMatch()
    {
        return $this->expectation->match($this->alias, $this->subject, $this->arguments);
    }

}
