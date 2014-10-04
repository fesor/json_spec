<?php

namespace JsonSpec\Behat\Consumer;

use JsonSpec\Behat\JsonProvider\JsonHolder;

/**
 * This interface is just for BC vs < 0.3 version
 * @deprecated will be removed in version 0.3
 */
interface JsonConsumer
{

    /**
     * @param string $json
     */
    public function setJson($json);

}
