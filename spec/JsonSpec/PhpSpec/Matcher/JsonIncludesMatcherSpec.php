<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use JsonSpec\Helper\FileHelper;
use JsonSpec\JsonSpecMatcher;
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

    public function it_should_provide_matcher_priority()
    {
        $this->getPriority()->shouldBe(50);
    }

    public function it_supports_correct_names()
    {
        $json = '"json"';
        $this->supports('includeJson', $json, [$json])->shouldBe(true);
        $this->supports('includeJsonFile', $json, [$json])->shouldBe(true);
        $this->supports('wrong_name', $json, [$json])->shouldBe(false);
    }

    public function it_supports_correct_arguments()
    {
        $json = '"json"';
        $this->supports('includeJson', $json, [$json])->shouldBe(true);
        $this->supports('includeJsonFile', $json, [$json])->shouldBe(true);
        $this->supports('includeJson', $json, [$json, []])->shouldBe(true);
        $this->supports('includeJsonFile', $json, [$json, []])->shouldBe(true);
        $this->supports('includeJson', $json, [[]])->shouldBe(false);
        $this->supports('includeJsonFile', $json, [[]])->shouldBe(false);
        $this->supports('includeJson', $json, [$json, 'not_options'])->shouldBe(false);
        $this->supports('includeJsonFile', $json, [$json, 'not_options'])->shouldBe(false);
    }

    public function it_supports_direct_comparison()
    {
        $this->supports('includeJson', '', array(''));
    }

    public function it_supports_loading_from_file()
    {
        $this->supports('includeJsonFile', '', array(''));
    }

    public function it_delegates_matching_to_json_spec_matcher()
    {
        $this->positive('["json", "spec"]', '"spec"');
    }

    public function it_loads_json_from_file_and_delegates_matching_to_json_spec_matcher(FileHelper $fileHelper)
    {
        $json = '["json", "spec"]';
        $this->setFileHelper($fileHelper);
        $fileHelper->loadJson('foo/bar.json')->willReturn('"spec"');

        $this->matcherMock->includes('["json", "spec"]', '"spec"', [])->willReturn(true);
        $this->shouldNotThrow()->duringPositiveMatch('includeJsonFile', $json, array('foo/bar.json', []));
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

    private function positive($actual, $json, $exception = null, array $options = [])
    {

        $this->matcherMock->includes($actual, $json, $options)->willReturn($exception === null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringPositiveMatch('jsonIncludes', $actual, array($json));
        } else {
            $this->shouldThrow($exception)->duringPositiveMatch('jsonIncludes', $actual, array($json));
        }
    }

    private function negative($actual, $json, $exception = null, array $options = [])
    {
        $this->matcherMock->includes($actual, $json, $options)->willReturn($exception !== null);
        if ($exception === null) {
            $this->shouldNotThrow()->duringNegativeMatch('jsonIncludes', $actual, array($json));
        } else {
            $this->shouldThrow($exception)->duringNegativeMatch('jsonIncludes', $actual, array($json));
        }
    }

}
