<?php

namespace JsonSpec\Behat\Provider;

use Behat\Mink\Mink;

/**
 * Class MinkContextDriver
 * @package JsonSpec\Behat\Provider
 */
class MinkContextDriver implements JsonProvider
{

    /**
     * @var Mink
     */
    private $mink;

    /**
     * @param Mink $mink
     */
    public function __construct(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->mink->getSession()->getPage()->getContent();
    }

}
