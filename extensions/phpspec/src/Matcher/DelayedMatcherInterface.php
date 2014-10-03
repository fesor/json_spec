<?php

namespace JsonSpec\PhpSpec\Matcher;

use PhpSpec\Matcher\MatcherInterface;

/**
 * Interface DelayedMatcherInterface
 * @package JsonSpec\PhpSpec\Matcher
 *
 * Allow to execute match later
 */
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
