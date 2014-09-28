<?php

namespace JsonSpec\Behat\Provider;

class JsonProviderVoter
{

    /**
     * @var JsonProvider[]
     */
    private $jsonProviders;

    function __construct()
    {
        $this->jsonProviders = [];
    }

    /**
     * @return string
     */
    public function getJson()
    {
        foreach($this->jsonProviders as $provider) {
            $json = $provider->getJson();
            if (!empty($json)) {
                return $json;
            }
        }

        return '';
    }

    /**
     * Clear all data in all providers
     */
    public function clear()
    {
        foreach($this->jsonProviders as $provider) {
            $provider->clear();
        }
    }

    public function addProvider(JsonProvider $provider)
    {
        $this->jsonProviders[] = $provider;
        usort($this->jsonProviders, function (JsonProvider $a, JsonProvider $b) {
            return $a->getPriority() - $b->getPriority();
        });
    }

}
