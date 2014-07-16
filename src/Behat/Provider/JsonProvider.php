<?php

namespace JsonSpec\Behat\Provider;

/**
 * Interface JsonProviderInterface
 * @package JsonSpec\Behat\Provider
 */
interface JsonProvider
{

    /**
     * Returns JSON response
     *
     * @return string
     */
    public function getJson();

}
