<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\JsonSpecMatcher;
use JsonSpec\MatcherOptions;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class JsonIncludesMatcherSpec extends ObjectBehavior
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
        $this->positive('["json", "spec"]', '"spec"');
    }

    public function it_should_throw_exception_on_missmatch()
    {
        $this->positive('["json", "spec"]', '"foo"', new FailureException('Expected included JSON'));
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching()
    {
        $this->negative('["json", "spec"]', '"spec"', new FailureException('Expected excluded JSON'));
    }

    public function it_should_not_throw_an_exception_on_missmatch_during_negative_matching()
    {
        $this->negative('["json", "spec"]', '"foo"');
    }

    private function positive($actual, $json, $exception = null)
    {

        $this->matcherMock->getOptions()->willReturn(new MatcherOptions());
        $this->matcherMock->includes($actual, $json)->willReturn($exception === null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('jsonIncludes', $actual, array($json));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('jsonIncludes', $actual, array($json));
        }
    }

    private function negative($actual, $json, $exception = null)
    {
        $this->matcherMock->getOptions()->willReturn(new MatcherOptions());
        $this->matcherMock->includes($actual, $json)->willReturn($exception !== null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('jsonIncludes', $actual, array($json));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('jsonIncludes', $actual, array($json));
        }
    }

}
