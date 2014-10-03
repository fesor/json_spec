<?php

namespace JsonSpec\Behat\Provider;

use JsonSpec\Behat\Wrapper\GuzzleClient;

/**
 * Class WebApiProvider
 * @package JsonSpec\Behat\Provider\Driver
 */
class WebApiProvider implements JsonProvider
{

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @param GuzzleClient $client
     */
    public function __constructor(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function getJson()
    {
        return (string) $this->client->getLastResponse()->getBody()->getContents();
    }

}
