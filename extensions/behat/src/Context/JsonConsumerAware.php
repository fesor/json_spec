<?php

namespace JsonSpec\Behat\Context;

use JsonSpec\Behat\Consumer\JsonConsumer;

interface JsonConsumerAware
{

    public function setJsonConsumer(JsonConsumer $consumer);

}
