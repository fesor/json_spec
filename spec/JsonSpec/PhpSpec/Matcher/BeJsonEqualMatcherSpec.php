<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\JsonSpecMatcher;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class BeJsonEqualMatcherSpec extends ObjectBehavior
{

    private $matcherMock;

    public function let(JsonSpecMatcher $matcher)
    {
        $this->matcherMock = $matcher;
        $this->beConstructedWith($matcher);
    }

    public function it_delegates_matching_to_json_spec_matcher()
    {
        $this->positive('{"json": "spec"}', '{"json": "spec"}');
    }

    public function it_should_throw_exception_on_missmatch()
    {
        $this->positive('{"json": "spec"}', '{"spec": "json"}', new FailureException('Expected equivalent JSON'));
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching()
    {
        $this->negative('{"json": "spec"}', '{"json": "spec"}', new FailureException('Expected inequivalent JSON'));
    }

    public function it_should_not_throw_an_exception_on_missmatch_during_negative_matching()
    {
        $this->negative('{"json": "spec"}', '{"spec": "json"}');
    }

    private function positive($actual, $expected, $exception = null)
    {
        $this->matcherMock->isEqual($actual, $expected)->willReturn($exception === null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', $actual, array($expected));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('beJsonEqual', $actual, array($expected));
        }
    }

    private function negative($actual, $expected, $exception = null)
    {
        $this->matcherMock->isEqual($actual, $expected)->willReturn($exception !== null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('beJsonEqual', $actual, array($expected));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('beJsonEqual', $actual, array($expected));
        }
    }

}
