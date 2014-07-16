<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use \JsonSpec\Behat\Context\JsonConsumerAware;
use \JsonSpec\Behat\Consumer\JsonConsumer;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext, JsonConsumerAware
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
