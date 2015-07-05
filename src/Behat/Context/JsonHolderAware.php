<?php

namespace JsonSpec\Behat\Context;

use JsonSpec\Behat\JsonProvider\JsonHolder;

interface JsonHolderAware
{
    /**
     * @param JsonHolder $holder
     */
    public function setJsonHolder(JsonHolder $holder);

}
