<?php

namespace spec\JsonSpec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonHelperSpec extends ObjectBehavior
{

    function it_should_parse_json()
    {
        $result = new \stdClass();
        $result->json = ['spec'];
        $this->parse('{"json":["spec"]}')->shouldBeLike($result);
    }

    function it_should_pars_JSON_values()
    {
        $this->parse('"json_spec"')->shouldBe('json_spec');
    }

    function it_should_raises_a_parser_error_for_invalid_JSON()
    {
        $this->shouldThrow()->duringParse('json_spec');
    }

    function it_should_correctly_validate_json()
    {
        $this->isValid('"json_spec"')->shouldBe(true);
        $this->isValid('json_spec')->shouldBe(false);
    }

    function it_should_normalize_json()
    {
        $normalizedJson =
'{
    "json": [
        "spec"
    ]
}';

        $this->normalize('{"json":["spec"]}')->shouldBe(rtrim($normalizedJson));
    }

    function it_should_normalize_json_value()
    {
        $this->normalize('1e+1')->shouldBe('10');
    }

    function it_should_generates_a_normalized_json_document()
    {
        $normalizedJson =
'{
    "json": [
        "spec"
    ]
}';
        $this->generateNormalizedJson(['json'=>['spec']])->shouldBe(rtrim($normalizedJson));
    }
}
