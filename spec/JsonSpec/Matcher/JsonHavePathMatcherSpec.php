<?php

namespace spec\JsonSpec\Matcher;

use JsonSpec\Helper\JsonHelper;
use JsonSpec\Matcher\MatcherOptions;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seld\JsonLint\JsonParser;

class JsonHavePathMatcherSpec extends ObjectBehavior
{

    function let(MatcherOptions $options)
    {
        $options->getPath()->willReturn('');

        $this->beConstructedWith(new JsonHelper(new JsonParser()), $options);
    }

    function it_matches_hash_keys()
    {
        $this->match('{"one":{"two":{"three":4}}}', 'one/two/three')->shouldBe(true);
    }

    function it_does_not_match_values()
    {
        $this->match('{"one":{"two":{"three":4}}}', 'one/two/three/4')->shouldBe(false);
    }

    function it_matches_array_indexes()
    {
        $this->match('[1,[1,2,[1,2,3,4]]]', '1/2/3')->shouldBe(true);
    }

    function it_respects_null_array_values()
    {
        $this->match('[null,[null,null,[null,null,null,null]]]', '1/2/3')->shouldBe(true);
    }

    function it_matches_hash_keys_and_array_indexes()
    {
        $this->match('{"one":[1,2,{"three":4}]}', 'one/2/three')->shouldBe(true);
    }

    function it_matches_hash_keys_with_given_base_path(MatcherOptions $options)
    {
        $options->getPath()->willReturn('one');
        $this->match('{"one":{"two":{"three":4}}}', 'two/three')->shouldBe(true);
    }

}
