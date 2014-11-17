<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\JsonSpecMatcher;
use JsonSpec\MatcherOptions;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class JsonHavePathMatcherSpec extends ObjectBehavior
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

    public function it_should_provide_matcher_priority()
    {
        $this->getPriority()->shouldBe(50);
    }

    public function it_supports_correct_names()
    {
        $json = '"json"';
        $this->supports('haveJsonPath', $json, [$json])->shouldBe(true);
        $this->supports('wrong_name', $json, [$json])->shouldBe(false);
    }

    public function it_supports_correct_arguments()
    {
        $json = '"json"';
        $this->supports('haveJsonPath', $json, [$json])->shouldBe(true);
        $this->supports('haveJsonPath', $json, [$json, []])->shouldBe(true);
        $this->supports('haveJsonPath', $json, [[]])->shouldBe(false);
        $this->supports('haveJsonPath', $json, [$json, 'not_options'])->shouldBe(false);
    }

    public function it_delegates_matching_to_json_spec_matcher()
    {
        $this->positive('{"json": ["spec"]}', 'json/0');
    }

    public function it_should_throw_exception_on_missmatch()
    {
        $this->positive('{"json": ["spec"]}', 'json/1', new FailureException('Expected JSON path "json/1"'));
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching()
    {
        $this->negative('{"json": ["spec"]}', 'json/0', new FailureException('Expected no JSON path "json/0"'));
    }

    public function it_should_not_throw_an_exception_on_missmatch_during_negative_matching()
    {
        $this->negative('{"json": ["spec"]}', 'json/1');
    }

    private function positive($actual, $path, $exception = null, array $options = [])
    {

        $this->matcherMock->havePath($actual, $path, $options)->willReturn($exception === null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('haveJsonPath', $actual, array($path));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('haveJsonPath', $actual, array($path));
        }
    }

    private function negative($actual, $path, $exception = null, array $options = [])
    {
        $this->matcherMock->havePath($actual, $path, $options)->willReturn($exception !== null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('haveJsonPath', $actual, array($path));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('haveJsonSize', $actual, array($path));
        }
    }

}
