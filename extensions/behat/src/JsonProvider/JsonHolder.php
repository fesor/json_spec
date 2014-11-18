<?php

namespace JsonSpec\Behat\JsonProvider;

class JsonHolder
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

}
