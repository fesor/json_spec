<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use JsonSpec\Behat\Context\JsonSpecContext;
use JsonSpec\Behat\Provider\JsonProvider;
use JsonSpec\Behat\Provider\JsonProviderVoter;
use JsonSpec\Helper\FileHelper;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\Helper\MemoryHelper;
use JsonSpec\JsonSpecMatcher;

class JsonSpecContextInitializer implements ContextInitializer
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
     * @var JsonProviderVoter
     */
    private $jsonProvider;

    /**
     * @param JsonProviderVoter    $jsonProvider
     * @param JsonSpecMatcher $matcher
     * @param JsonHelper      $jsonHelper
     * @param FileHelper      $fileHelper
     * @param MemoryHelper    $memoryHelper
     */
    public function __construct(
        JsonProviderVoter $jsonProvider,
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
     * @inheritdoc
     */
    public function initializeContext(Context $context)
    {
        if (!$this->supports($context)) {
            return;
        }

        $context->init($this->jsonProvider, $this->jsonSpecMatcher, $this->memoryHelper, $this->fileHelper, $this->jsonHelper);
    }


    /**
     * Checks if initializer supports provided context.
     *
     * @param Context $context
     *
     * @return boolean
     */
    private function supports(Context $context)
    {
        return $context instanceof JsonSpecContext;
    }

}
