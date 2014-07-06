<?php

namespace spec\JsonSpec\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MatcherConfigurationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array('id', 'created_at'));
    }

    function it_allows_to_define_json_path()
    {
        $this->atPath('json/path');
        $this->getPath()->shouldBe('json/path');
    }

    function it_allows_to_add_excluded_kes()
    {
        $this->getExcludedKeys()->shouldHaveCount(2);
        $this->excluding('custom', 'key');
        $this->getExcludedKeys()->shouldHaveCount(4);
    }

    function it_allows_to_include_some_keys()
    {
        $this->getExcludedKeys()->shouldHaveCount(2);
        $this->including('id');
        $this->getExcludedKeys()->shouldHaveCount(1);
        $this->getExcludedKeys()->shouldContain('created_at');
    }
}
