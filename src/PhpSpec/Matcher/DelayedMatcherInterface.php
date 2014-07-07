<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Matcher\MatcherInterface;

interface DelayedMatcherInterface extends MatcherInterface
{

    /**
     * This method called as a promise,
     * that positiveMatch/negativeMatch will be called later
     *
     * @return mixed
     */
    public function promise();

}
