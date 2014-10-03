<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use JsonSpec\Behat\Context\JsonConsumerAware;
use JsonSpec\Behat\Consumer\JsonConsumer;

/**
 * Class JsonConsumerAwareInitializer
 * @package JsonSpec\Behat\Context\Initializer
 */
class JsonConsumerAwareInitializer implements ContextInitializer
{

    /**
     * @var JsonConsumer
     */
    private $jsonConsumer;

    /**
     * @param JsonConsumer $jsonConsumer
     */
    public function __construct(JsonConsumer $jsonConsumer)
    {
        $this->jsonConsumer = $jsonConsumer;
    }

    /**
     * @inheritdoc
     */
    public function initializeContext(Context $context)
    {
        if (!$this->supports($context)) {
            return;
        }

        $context->setJsonConsumer($this->jsonConsumer);
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
        return $context instanceof JsonConsumerAware;
    }

}
