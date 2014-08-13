<?php

namespace spec\JsonSpec;

use PhpSpec\ObjectBehavior;

class MatcherOptionsSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(array('id', 'created_at'));
    }

    public function it_allows_to_define_json_path()
    {
        $this->atPath('json/path');
        $this->getPath()->shouldBe('json/path');
    }

    public function it_allows_to_add_excluded_kes()
    {
        $this->getExcludedKeys()->shouldHaveCount(2);
        $this->excluding('custom', 'key');
        $this->getExcludedKeys()->shouldHaveCount(4);
    }

    public function it_allows_to_include_some_keys()
    {
        $this->getExcludedKeys()->shouldHaveCount(2);
        $this->including('id');
        $this->getExcludedKeys()->shouldHaveCount(1);
        $this->getExcludedKeys()->shouldContain('created_at');
    }
}
