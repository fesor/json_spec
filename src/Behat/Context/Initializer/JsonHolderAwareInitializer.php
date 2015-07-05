<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use JsonSpec\Behat\Context\JsonConsumerAware;
use JsonSpec\Behat\Consumer\JsonConsumer;
use JsonSpec\Behat\Context\JsonHolderAware;
use JsonSpec\Behat\JsonProvider\JsonHolder;

/**
 * Class JsonHolderAwareInitializer
 * @package JsonSpec\Behat\Context\Initializer
 */
class JsonHolderAwareInitializer implements ContextInitializer
{

    /**
     * @var JsonHolder
     */
    private $jsonHolder;

    /**
     * @param JsonHolder $jsonHolder
     */
    public function __construct(JsonHolder $jsonHolder)
    {
        $this->jsonHolder = $jsonHolder;
    }

    /**
     * @inheritdoc
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof JsonHolderAware) {
            $context->setJsonHolder($this->jsonHolder);
        }
    }

}
