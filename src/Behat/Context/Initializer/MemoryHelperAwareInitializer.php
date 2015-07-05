<?php

namespace JsonSpec\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use JsonSpec\Behat\Context\MemoryHelperAware;
use JsonSpec\Behat\Helper\MemoryHelper;

/**
 * Class MemoryHelperAwareInitializer
 * @package JsonSpec\Behat\Context\Initializer
 */
class MemoryHelperAwareInitializer implements ContextInitializer
{

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @param MemoryHelper $memoryHelper
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
