<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\JsonSpecMatcher;
use JsonSpec\MatcherOptions;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class JsonHaveSizeMatcherSpec extends ObjectBehavior
{
    /**
     * @var JsonSpecMatcher
     */
    private $matcherMock;

    public function let(JsonSpecMatcher $matcher)
    {
        $this->matcherMock = $matcher;
        $this->beConstructedWith($matcher);
    }

    public function it_delegates_matching_to_json_spec_matcher()
    {
        $this->positive('["json", "spec"]', 2);
    }

    public function it_should_throw_exception_on_missmatch()
    {
        $this->positive('["json"]', 2, new FailureException('Expected JSON value size to be 2'));
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching()
    {
        $this->negative('["json", "spec"]', 2, new FailureException('Expected JSON value size to not be 2'));
    }

    public function it_should_not_throw_an_exception_on_missmatch_during_negative_matching()
    {
        $this->negative('["json"]', 2);
    }

    private function positive($actual, $size, $exception = null)
    {

        $this->matcherMock->getOptions()->willReturn(new MatcherOptions());
        $this->matcherMock->haveSize($actual, $size)->willReturn($exception === null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('haveJsonType', $actual, array($size));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('haveJsonType', $actual, array($size));
        }
    }

    private function negative($actual, $size, $exception = null)
    {
        $this->matcherMock->getOptions()->willReturn(new MatcherOptions());
        $this->matcherMock->haveSize($actual, $size)->willReturn($exception !== null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('haveJsonSize', $actual, array($size));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('haveJsonSize', $actual, array($size));
        }
    }

}
