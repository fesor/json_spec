<?php

use \Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use \JsonSpec\Behat\Context\JsonConsumerAware;
use \JsonSpec\Behat\Consumer\JsonConsumer;

/**
 * Behat context class.
 */
class FeatureContext implements Context, JsonConsumerAware
{
    /**
     * @var JsonConsumer
     */
    private $jsonConsumer;

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
        $this->jsonConsumer->setJson($this->json);
    }

    /**
     * @inheritdoc
     */
    public function setJsonConsumer(JsonConsumer $consumer)
    {
        $this->jsonConsumer = $consumer;
    }

}
