<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use JsonSpec\Behat\Consumer\JsonConsumer;
use JsonSpec\Behat\Context\JsonConsumerAware;

class JsonSpecInitializer implements ContextInitializer
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
        if (!$context instanceof JsonConsumerAware) {
            return;
        }

        $context->setJsonConsumer($this->jsonConsumer);
    }

}
