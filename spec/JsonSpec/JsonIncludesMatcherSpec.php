<?php

namespace spec\JsonSpec;

use JsonSpec\Exception\NotIncludedException;
use JsonSpec\Helper\JsonHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seld\JsonLint\JsonParser;

class JsonIncludesMatcherSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new JsonHelper(new JsonParser()));
    }

    function it_checks_is_json_part_is_included()
    {
        $this->shouldNotThrow()->duringIsIncludes('["json", "spec"]', '"spec"');
        $this->shouldThrow(
            new NotIncludedException('"spec"')
        )->duringIsIncludes('["no-json", "no-spec"]', '"spec"');
    }

    function it_checks_is_json_part_is_included_at_given_path()
    {
        $this->shouldNotThrow()->duringIsIncludes('{"json": ["spec"]}', '"spec"', 'json');
        $this->shouldThrow(
            new NotIncludedException('"spec"')
        )->duringIsIncludes('{"json": ["no-spec"]}', '"spec"');
    }
}
