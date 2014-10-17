<?php

namespace JsonSpec\Behat\Context\Initializer;

use JsonSpec\Behat\Helper\MemoryHelper;

interface MemoryHelperAware
{

    /**
     * @param MemoryHelper $memoryHelper
     */
    public function setMemoryHelper(MemoryHelper $memoryHelper);

}
