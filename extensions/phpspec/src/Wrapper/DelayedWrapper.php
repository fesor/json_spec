<?php

namespace JsonSpec\PhpSpec\Wrapper;

use JsonSpec\PhpSpec\Wrapper\Subject\Expectation\DelayedExpectationFactory;
use JsonSpec\PhpSpec\Wrapper\Subject\DelayedSubject;
use JsonSpec\PhpSpec\Wrapper\Subject\Expectation\DelayedExpectationManager;
use PhpSpec\Exception\ExceptionFactory;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Subject;
use PhpSpec\Wrapper\Subject\Caller;
use PhpSpec\Wrapper\Subject\SubjectWithArrayAccess;
use PhpSpec\Wrapper\Subject\WrappedObject;
use PhpSpec\Wrapper\Wrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DelayedWrapper extends Wrapper
{

    /**
     * @var \PhpSpec\Runner\MatcherManager
     */
    private $matchers;
    /**
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var \PhpSpec\Loader\Node\ExampleNode
     */
    private $example;

    /**
     * @var DelayedExpectationManager
     */
    private $manager;

    /**
     * @param MatcherManager           $matchers
     * @param PresenterInterface       $presenter
     * @param EventDispatcherInterface $dispatcher
     * @param ExampleNode              $example
     */
    public function __construct(MatcherManager $matchers, PresenterInterface $presenter,
                                EventDispatcherInterface $dispatcher, ExampleNode $example,
                                DelayedExpectationManager $manager)
    {
        $this->matchers = $matchers;
        $this->presenter = $presenter;
        $this->dispatcher = $dispatcher;
        $this->example = $example;
        $this->manager = $manager;
    }

    public function wrap($value = null)
    {
        $exceptionFactory   = new ExceptionFactory($this->presenter);
        $wrappedObject      = new WrappedObject($value, $this->presenter);
        $caller             = new Caller($wrappedObject, $this->example, $this->dispatcher, $exceptionFactory, $this);
        $arrayAccess        = new SubjectWithArrayAccess($caller, $this->presenter, $this->dispatcher);
        $expectationFactory = new DelayedExpectationFactory($this->example, $this->dispatcher, $this->matchers, $this->manager);

        return new DelayedSubject(
            $value, $this, $wrappedObject, $caller, $arrayAccess, $expectationFactory
        );
    }

}
