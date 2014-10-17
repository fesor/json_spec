<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use JsonSpec\Behat\Context\JsonConsumerAware;
use JsonSpec\Behat\Consumer\JsonConsumer;
use JsonSpec\Behat\Context\JsonHolderAware;
use JsonSpec\Behat\Helper\MemoryHelper;
use JsonSpec\Behat\JsonProvider\JsonHolder;

/**
 * Class JsonConsumerAwareInitializer
 * @package JsonSpec\Behat\Context\Initializer
 */
class MemoryHelperAwareInitializer implements ContextInitializer
{

    /**
     * @var JsonConsumer
     */
    private $memoryHelper;

    /**
     * @param JsonHolder $jsonHolder
     */
    public function __construct(MemoryHelper $memoryHelper)
    {
        $this->memoryHelper = $memoryHelper;
    }

    /**
     * @inheritdoc
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof MemoryHelperAware) {
            $context->setMemoryHelper($this->memoryHelper);
        }
    }

}
