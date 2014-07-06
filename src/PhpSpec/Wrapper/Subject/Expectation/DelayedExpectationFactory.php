<?php

namespace JsonSpec\PhpSpec\Wrapper\Subject\Expectation;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Matcher\MatcherInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Subject\Expectation\ExpectationInterface;
use PhpSpec\Wrapper\Subject\ExpectationFactory;
use PhpSpec\Wrapper\Unwrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DelayedExpectationFactory
{

    /**
     * @var \PhpSpec\Loader\Node\ExampleNode
     */
    private $example;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var \PhpSpec\Runner\MatcherManager
     */
    private $matchers;


    /**
     * @var ExpectationFactory
     */
    private $expectationFactory;

    /**
     * @var DelayedExpectationManager
     */
    private $manager;

    /**
     * @param ExampleNode              $example
     * @param EventDispatcherInterface $dispatcher
     * @param MatcherManager           $matchers
     */
    public function __construct(ExampleNode $example, EventDispatcherInterface $dispatcher, MatcherManager $matchers, DelayedExpectationManager $manager)
    {
        $this->example = $example;
        $this->dispatcher = $dispatcher;
        $this->matchers = $matchers;
        $this->expectationFactory = new ExpectationFactory($example, $dispatcher, $matchers);
        $this->manager = $manager;
    }


    /**
     * @param string $expectation
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return ExpectationInterface
     */
    public function create($expectation, $subject, array $arguments = array())
    {

        $name = lcfirst(preg_replace('/^should(Not)?/', '', $expectation));
        $expectation = $this->expectationFactory->create($expectation, $subject, $arguments);
        if ($name === 'throw') {
            return $expectation;
        }

        $matcher = $this->findMatcher($name, $subject, $arguments);

        return new DelayedDecorator($expectation, $matcher, $this->manager);
    }

    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return MatcherInterface
     */
    private function findMatcher($name, $subject, array $arguments = array())
    {
        $unwrapper = new Unwrapper();
        $arguments = $unwrapper->unwrapAll($arguments);

        return $this->matchers->find($name, $subject, $arguments);
    }

}