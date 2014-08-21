<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use JsonSpec\Behat\Context\JsonSpecContext;
use JsonSpec\Behat\Provider\JsonProvider;
use JsonSpec\Helper\FileHelper;
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
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @var JsonProvider
     */
    private $jsonProvider;

    /**
     * @param JsonProvider    $jsonProvider
     * @param JsonSpecMatcher $matcher
     * @param JsonHelper      $jsonHelper
     * @param FileHelper      $fileHelper
     * @param MemoryHelper    $memoryHelper
     */
    public function __construct(
        JsonProvider $jsonProvider,
        JsonSpecMatcher $matcher,
        JsonHelper $jsonHelper,
        FileHelper $fileHelper,
        MemoryHelper $memoryHelper
    )
    {
        $this->jsonProvider = $jsonProvider;
        $this->jsonSpecMatcher = $matcher;
        $this->fileHelper = $fileHelper;
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
        $context->init($this->jsonProvider, $this->jsonSpecMatcher, $this->memoryHelper, $this->fileHelper, $this->jsonHelper);
    }

}
