<?php

namespace spec\JsonSpec\Matcher;

use JsonSpec\Helper\JsonHelper;
use JsonSpec\Matcher\MatcherOptions;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seld\JsonLint\JsonParser;

class BeJsonEqualMatcherSpec extends ObjectBehavior
{
    function let(MatcherOptions $options)
    {
        $options->getPath()->shouldBeCalled();
        $options->getExcludedKeys()->shouldBeCalled()->willReturn(array('id'));
        $this->beConstructedWith(new JsonHelper(new JsonParser()), $options);
    }

    function it_matches_identical_JSON(MatcherOptions $options)
    {
        $this->match('{"json":"spec"}', '{"json":"spec"}')->shouldBe(true);
    }

    function it_matches_differently_formatted_JSON()
    {
        $this->match('{"json": "spec"}', '{"json":"spec"}')->shouldBe(true);
    }

    function it_matches_out_of_order_hashes()
    {
        $this->match('{"laser":"lemon","json":"spec"}', '{"json":"spec","laser":"lemon"}')->shouldBe(true);
    }

    function it_does_not_match_out_of_order_arrays()
    {
        $this->match('["json","spec"]', '["spec", "json"]')->shouldBe(false);
    }

    function it_matches_valid_JSON_values_yet_invalid_JSON_documents()
    {
        $this->match('"json_spec"', '"json_spec"')->shouldBe(true);
    }

    function it_matches_at_a_path(MatcherOptions $options)
    {
        $options->getPath()->willReturn('json/0');
        $this->match('{"json":["spec"]}', '"spec"')->shouldBe(true);
    }

    function it_ignores_excluded_by_default_hash_keys()
    {
        $this->match('{"id": 1, "json":["spec"]}', '{"id": 2, "json":["spec"]}')->shouldBe(true);
    }

    function it_ignores_custom_excluded_hash_keys(MatcherOptions $options)
    {
        $options->getExcludedKeys()->willReturn(array('ignore'));
        $this->match('{"json":"spec","ignore":"please"}', '{"json":"spec"}')->shouldBe(true);
    }

    function it_ignores_nested_excluded_hash_keys(MatcherOptions $options)
    {
        $options->getExcludedKeys()->willReturn(array('ignore'));
        $this->match('{"json":"spec","please":{"ignore":"this"}}', '{"json":"spec","please":{}}')->shouldBe(true);
    }

    function it_ignores_hash_keys_when_included_in_the_expected_value(MatcherOptions $options)
    {
        $options->getExcludedKeys()->willReturn(array('ignore'));
        $this->match('{"json":"spec","ignore":"please"}', '{"json":"spec","ignore":"this"}')->shouldBe(true);
    }

    function it_matches_different_looking_JSON_equivalent_values()
    {
        $this->match('{"ten":10.0}', '{"ten":1e+1}')->shouldBe(true);
    }

    function it_excludes_multiple_keys(MatcherOptions $options)
    {
        $options->getExcludedKeys()->willReturn(array('id', 'json'));
        $this->match('{"id":1,"json":"spec"}', '{"id":2,"json":"different"}')->shouldBe(true);
    }


}
