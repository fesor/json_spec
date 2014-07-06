<?php

namespace spec\JsonSpec\Matcher;

use JsonSpec\Helper\JsonHelper;
use JsonSpec\Matcher\Matcher;
use JsonSpec\Matcher\MatcherOptions;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seld\JsonLint\JsonParser;

class JsonHaveTypeMatcherSpec extends ObjectBehavior
{

    function let(MatcherOptions $options)
    {
        $this->beConstructedWith(new JsonHelper(new JsonParser()), $options);
    }

    function it_matches_objects()
    {
        $this->match('{}', 'object')->shouldBe(true);
    }

    function it_matches_arrays()
    {
        $this->match('[]', 'array')->shouldBe(true);
    }

    function it_matches_at_a_path(MatcherOptions $options)
    {
        $options->getPath()->willReturn('root');
        $this->match('{"root":[]}', 'array')->shouldBe(true);
    }

    function it_matches_strings(MatcherOptions $options)
    {
        $options->getPath()->willReturn('0');
        $this->match('["json_spec"]', 'string')->shouldBe(true);
    }

    function it_matches_a_valid_JSON_value_yet_invalid_JSON_document()
    {
        $this->match('"json_spec"', 'string')->shouldBe(true);
    }

    function it_matches_empty_strings()
    {
        $this->match('""', 'string')->shouldBe(true);
    }

    function it_matches_integers()
    {
        $this->match('10', 'integer')->shouldBe(true);
    }

    function it_matches_floats()
    {
        $this->match('10.0', 'float')->shouldBe(true);
        $this->match('1e+1', 'float')->shouldBe(true);
    }

    function it_matches_booleans()
    {
        $this->match('true', 'boolean')->shouldBe(true);
        $this->match('false', 'boolean')->shouldBe(true);
    }

}
