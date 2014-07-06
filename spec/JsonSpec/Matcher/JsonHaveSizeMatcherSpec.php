<?php

namespace spec\JsonSpec\Matcher;

use JsonSpec\Helper\JsonHelper;
use JsonSpec\Matcher\MatcherOptions;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seld\JsonLint\JsonParser;

class JsonHaveSizeMatcherSpec extends ObjectBehavior
{

    function let(MatcherOptions $options)
    {
        $this->beConstructedWith(new JsonHelper(new JsonParser()), $options);
    }

    function it_counts_array_entries()
    {
        $this->match('[1,2,3]', 3)->shouldBe(true);
    }

    function it_counts_null_array_entries()
    {
        $this->match('[1,null,3]', 3)->shouldBe(true);
    }

    function it_counts_hash_key_value_pairs()
    {
        $this->match('{"one":1,"two":2,"three":3}', 3)->shouldBe(true);
    }

    function it_counts_null_hash_values()
    {
        $this->match('{"one":1,"two":null,"three":3}', 3)->shouldBe(true);
    }

    function it_matches_at_a_path(MatcherOptions $options)
    {
        $options->getPath()->willReturn('one');
        $this->match('{"one":[1,2,3]}', 3)->shouldBe(true);
    }

}
