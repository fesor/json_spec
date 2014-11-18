<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\Helper\FileHelper;
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

    public function it_supports_direct_comparison()
    {
        $this->supports('beJsonEqual', '', array(''));
    }

    public function it_supports_loading_from_file()
    {
        $this->supports('beJsonEqualFile', '', array(''));
    }

    public function it_supports_correct_arguments()
    {
        $json = '"json"';
        $this->supports('beJsonEqual', $json, [$json])->shouldBe(true);
        $this->supports('beJsonEqualFile', $json, [$json])->shouldBe(true);
        $this->supports('beJsonEqual', $json, [$json, []])->shouldBe(true);
        $this->supports('beJsonEqualFile', $json, [$json, []])->shouldBe(true);
        $this->supports('beJsonEqual', $json, [[]])->shouldBe(false);
        $this->supports('beJsonEqualFile', $json, [[]])->shouldBe(false);
        $this->supports('beJsonEqual', $json, [$json, 'not_options'])->shouldBe(false);
        $this->supports('beJsonEqualFile', $json, [$json, 'not_options'])->shouldBe(false);
    }

    public function it_should_provide_matcher_priority()
    {
        $this->getPriority()->shouldBe(50);
    }

    public function it_delegates_matching_to_json_spec_matcher()
    {
        $this->positive('{"json": "spec"}', '{"json": "spec"}');
    }

    public function it_loads_json_from_file_and_delegates_matching_to_json_spec_matcher(FileHelper $fileHelper)
    {
        $json = '{"json": "spec"}';
        $this->setFileHelper($fileHelper);
        $fileHelper->loadJson('foo/bar.json')->willReturn($json);

        $this->matcherMock->isEqual($json, $json, [])->willReturn(true);
        $this->shouldNotThrow()->duringPositiveMatch('beJsonEqualFile', $json, array('foo/bar.json', []));
    }

    public function it_should_throw_exception_on_missmatch()
    {
        $this->positive('{"json": "spec"}', '{"spec": "json"}', new FailureException('Expected equivalent JSON'));
    }

    public function it_should_throw_exception_on_missmatch_by_given_path()
    {
        $this->positive('{"json": "spec"}', '{"json": "json"}',
            new FailureException('Expected equivalent JSON at path \'json\''), [
                JsonSpecMatcher::OPTION_PATH => 'json'
            ]
        );
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching()
    {
        $this->negative('{"json": "spec"}', '{"json": "spec"}', new FailureException('Expected inequivalent JSON'));
    }

    public function it_should_throw_an_exception_on_json_match_during_negative_matching_by_given_path()
    {
        $this->negative('{"json": "spec"}', '{"json": "spec"}',
            new FailureException('Expected inequivalent JSON at path \'json\''), [
                JsonSpecMatcher::OPTION_PATH => 'json'
            ]
        );
    }

    public function it_should_not_throw_an_exception_on_missmatch_during_negative_matching()
    {
        $this->negative('{"json": "spec"}', '{"spec": "json"}');
    }

    private function positive($actual, $expected, $exception = null, array $options = [])
    {
        $this->matcherMock->isEqual($actual, $expected, $options)->willReturn($exception === null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', $actual, array($expected, $options));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('beJsonEqual', $actual, array($expected, $options));
        }
    }

    private function negative($actual, $expected, $exception = null, array $options = [])
    {
        $this->matcherMock->isEqual($actual, $expected, $options)->willReturn($exception !== null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('beJsonEqual', $actual, array($expected, $options));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('beJsonEqual', $actual, array($expected, $options));
        }
    }

}
