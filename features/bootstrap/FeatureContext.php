<?php

use \Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use \JsonSpec\Behat\Context\JsonHolderAware;
use \JsonSpec\Behat\JsonProvider\JsonHolder;

/**
 * Behat context class.
 */
class FeatureContext implements Context, JsonHolderAware
{
    /**
     * @var JsonHolder
     */
    private $jsonHolder;

    /**
     * @var string
     */
    private $json;

    /**
     * @Given the JSON is:
     */
    public function jsonIs(PyStringNode $json)
    {
        $this->json = $json->getRaw();
    }

    /**
     * @Given I get the JSON
     */
    public function getJson()
    {
        $this->jsonHolder->setJson($this->json);
    }

    /**
     * @param JsonHolder $holder
     */
    public function setJsonHolder(JsonHolder $holder)
    {
        $this->jsonHolder = $holder;
    }


}
