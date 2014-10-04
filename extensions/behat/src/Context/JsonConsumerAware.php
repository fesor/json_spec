<?php

namespace JsonSpec\Behat\Context;

use JsonSpec\Behat\Consumer\JsonConsumer;

/**
 * Interface JsonConsumerAware
 * @package JsonSpec\Behat\Context
 * @deprecated use JsonHolderAware instead. JsonConsumer will be removed in version 0.3
 */
interface JsonConsumerAware
{

    public function setJsonConsumer(JsonConsumer $consumer);

}
