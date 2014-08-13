<?php

namespace JsonSpec\Behat\Context\Initializer;

use JsonSpec\Behat\Context\JsonConsumerAware;
use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use JsonSpec\Behat\Consumer\JsonConsumer;

/**
 * Class JsonConsumerAwareInitializer
 * @package JsonSpec\Behat\Context\Initializer
 */
class JsonConsumerAwareInitializer implements InitializerInterface
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
     * Checks if initializer supports provided context.
     *
     * @param ContextInterface $context
     *
     * @return Boolean
     */
    public function supports(ContextInterface $context)
    {
        return $context instanceof JsonConsumerAware;
    }

    /**
     * Initializes provided context.
     *
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        /** @var JsonConsumerAware $context */
        $context->setJsonConsumer($this->jsonConsumer);
    }

}
