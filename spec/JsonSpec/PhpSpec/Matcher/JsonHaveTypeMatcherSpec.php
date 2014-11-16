<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\JsonSpecMatcher;
use JsonSpec\MatcherOptions;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class JsonHaveTypeMatcherSpec extends ObjectBehavior
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
        $this->positive('"string"', 'string');
    }

    public function it_should_throw_exception_on_missmatch()
    {
        $this->positive('"string"', 'integer', new FailureException('Expected JSON value type to be integer'));
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching()
    {
        $this->negative('"string"', 'string', new FailureException('Expected JSON value type to not be string'));
    }

    public function it_should_not_throw_an_exception_on_missmatch_during_negative_matching()
    {
        $this->negative('"string"', 'integer');
    }

    private function positive($actual, $type, $exception = null, array $options = [])
    {

        $this->matcherMock->haveType($actual, $type, $options)->willReturn($exception === null);

        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('haveJsonType', $actual, array($type));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('haveJsonType', $actual, array($type));
        }
    }

    private function negative($actual, $type, $exception = null, array $options = [])
    {
        $this->matcherMock->haveType($actual, $type, $options)->willReturn($exception !== null);

        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('haveJsonType', $actual, array($type));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('haveJsonType', $actual, array($type));
        }
    }

}
