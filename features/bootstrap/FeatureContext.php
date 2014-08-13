<?php

use \Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use \JsonSpec\Behat\Context\JsonConsumerAware;
use \JsonSpec\Behat\Consumer\JsonConsumer;
use \JsonSpec\Behat\Context\JsonSpecContext;

/**
 * Behat context class.
 */
class FeatureContext extends BehatContext implements JsonConsumerAware
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
     * Attach sub-contexts
     */
    public function __construct()
    {
        $this->useContext('json_spec', new JsonSpecContext());
    }

    /**
     * @Given the JSON is:
     */
    function jsonIs(PyStringNode $json)
    {
        $this->json = $json->getRaw();
    }

    /**
     * @Given I get the JSON
     */
    function getJson()
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
