<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Fesor\JsonMatcher\JsonMatcherFactory;
use JsonSpec\Behat\Context\JsonMatcherAware;

/**
 * Class JsonMatcherAwareInitializer
 * @package JsonSpec\Behat\Context\Initializer
 */
class JsonMatcherAwareInitializer implements ContextInitializer
{

    /**
     * @var JsonMatcherFactory
     */
    private $jsonMatcherFactory;

    /**
     * @param JsonMatcherFactory $memoryHelper
     */
    public function __construct(JsonMatcherFactory $memoryHelper)
    {
        $this->jsonMatcherFactory = $memoryHelper;
    }

    /**
     * @inheritdoc
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof JsonMatcherAware || $this->usesTrait($context)) {
            $context->setJsonMatcher($this->jsonMatcherFactory);
        }
    }

    private function usesTrait(Context $context)
    {
        $usedTraits = class_uses($context);

        return is_array($usedTraits) && in_array(
            'JsonSpec\\Behat\\Context\\Traits\\JsonMatcherAwareTrait', $usedTraits
        );
    }

}
