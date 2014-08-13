<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use JsonSpec\Behat\Context\JsonSpecContext;
use JsonSpec\Behat\Provider\JsonProvider;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\Helper\MemoryHelper;
use JsonSpec\JsonSpecMatcher;

class JsonSpecContextInitializer implements InitializerInterface
{

    /**
     * @var JsonProvider
     */
    private $jsonHelper;

    /**
     * @var JsonSpecMatcher
     */
    private $jsonSpecMatcher;

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @var JsonProvider
     */
    private $jsonProvider;

    /**
     * @param JsonProvider $jsonProvider
     */
    public function __construct(
        JsonProvider $jsonProvider,
        JsonSpecMatcher $matcher,
        JsonHelper $jsonHelper,
        MemoryHelper $memoryHelper
    )
    {
        $this->jsonProvider = $jsonProvider;
        $this->jsonSpecMatcher = $matcher;
        $this->memoryHelper = $memoryHelper;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * Checks if initializer supports provided context.
     *
     * @param ContextInterface $context
     *
     * @return Boolean
     */
    public function supports(ContextInterface $context)
    {
        return $context instanceof JsonSpecContext;
    }

    /**
     * Initializes provided context.
     *
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        $context->init($this->jsonProvider, $this->jsonSpecMatcher, $this->memoryHelper, $this->jsonHelper);
    }

}
