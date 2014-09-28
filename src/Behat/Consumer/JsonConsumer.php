<?php

namespace JsonSpec\Behat\Consumer;

use JsonSpec\Behat\Provider\JsonProvider;

class JsonConsumer implements JsonProvider
{
    /**
     * @var string
     */
    protected $lastJson;

    /**
     * Set last json response
     *
     * @param string $json
     */
    public function setJson($json)
    {
        $this->lastJson = $json;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->lastJson;
    }


    /**
     * @inheritdoc
     */
    public function clear()
    {
        $this->lastJson = null;
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 50;
    }

}
