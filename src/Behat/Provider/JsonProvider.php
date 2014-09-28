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

    /**
     * Returns priority of provider
     *
     * @return integer
     */
    public function getPriority();

    /**
     * Clears last used json
     */
    public function clear();

}
