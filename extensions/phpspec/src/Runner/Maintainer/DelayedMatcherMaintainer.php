<?php

namespace JsonSpec\PhpSpec\Runner\Maintainer;

use JsonSpec\PhpSpec\Wrapper\DelayedWrapper;
use JsonSpec\PhpSpec\Wrapper\Subject\Expectation\DelayedExpectationManager;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Wrapper\Unwrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DelayedMatcherMaintainer implements MaintainerInterface
{

    /**
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
    /**
     * @var \PhpSpec\Wrapper\Unwrapper
     */
    private $unwrapper;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var DelayedExpectationManager
     */
    private $manager;

    /**
     * @param PresenterInterface       $presenter
     * @param Unwrapper                $unwrapper
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(PresenterInterface $presenter, Unwrapper $unwrapper,
                                EventDispatcherInterface $dispatcher)
    {
        $this->presenter = $presenter;
        $this->unwrapper = $unwrapper;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ExampleNode $example
     *
     * @return boolean
     */
    public function supports(ExampleNode $example)
    {
        return $example->getSpecification()->getClassReflection()->implementsInterface(
            'PhpSpec\Wrapper\SubjectContainerInterface'
        );
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        // rewrap subject with DelayedDecorator
        $this->manager = new DelayedExpectationManager();
        $subjectFactory = new DelayedWrapper($matchers, $this->presenter, $this->dispatcher, $example, $this->manager);
        $subject = $subjectFactory->wrap(null);
        $subject->beAnInstanceOf(
            $example->getSpecification()->getResource()->getSrcClassname()
        );

        $context->setSpecificationSubject($subject);
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->manager->invoke();
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 90;
    }

}
