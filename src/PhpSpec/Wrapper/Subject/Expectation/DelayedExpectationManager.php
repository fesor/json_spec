<?php

namespace JsonSpec\PhpSpec\Wrapper\Subject\Expectation;

class DelayedExpectationManager
{

    /**
     * @var DelayedDecorator
     */
    private $expectation;

    public function add(DelayedDecorator $expectation)
    {
        $this->expectation = $expectation;
    }

    public function invoke()
    {
        if ($this->expectation) {
            $this->expectation->delayedMatch();
            $this->expectation = null;
        }
    }

}
