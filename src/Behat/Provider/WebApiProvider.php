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
     * @var string
     */
    private $currentJson;

    /**
     * @param GuzzleClient $client
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function getJson()
    {
        if (empty($this->currentJson)) {
            $lastResponse = $this->client->getLastResponse();
            if ($lastResponse) {
                $this->currentJson = (string) $this->client->getLastResponse()->getBody();
            } else {
                $this->currentJson = '';
            }
        }
        return $this->currentJson;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $this->currentJson = '';
    }


    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 10;
    }

}
