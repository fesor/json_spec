<?php

namespace spec\JsonSpec\Helper;

use JsonSpec\Exception\MissingPathException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seld\JsonLint\JsonParser;

class JsonHelperSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(new JsonParser());
    }

    function it_parses_json()
    {
        $result = new \stdClass();
        $result->json = ['spec'];
        $this->parse('{"json":["spec"]}')->shouldBeLike($result);
    }

    function it_parses_JSON_values()
    {
        $this->parse('"json_spec"')->shouldBe('json_spec');
        $this->parse('10')->shouldBe(10);
        $this->parse('null')->shouldBe(null);
    }

    function it_raises_a_parser_error_for_invalid_JSON()
    {
        $this->shouldThrow()->duringParse('json_spec');
    }

    function it_parses_at_a_path_if_given()
    {
        $json = '{"json": ["spec"]}';
        $this->parse($json, 'json')->shouldBeLike(["spec"]);
        $this->parse($json, 'json/0')->shouldBe('spec');
    }

    function it_raises_an_error_for_a_missing_path()
    {
        $json = '{"json": ["spec"]}';
        $this->shouldThrow(
            new MissingPathException('json/1')
        )->duringParse($json, 'json/1');
    }

    function it_parses_at_a_numeric_string_path()
    {
        $json = '{"1": "json"}';
        $this->parse($json, '1')->shouldBe('json');
    }

    function it_correctly_validate_json_value()
    {
        $this->isValid('"json_spec"')->shouldBe(true);
        $this->isValid('json_spec')->shouldBe(false);
    }

    function it_normalize_json()
    {
        $normalizedJson =
            '{
    "json": [
        "spec"
    ]
}';

        $this->normalize('{"json":["spec"]}')->shouldBe(rtrim($normalizedJson));
    }

    function it_normalize_json_value()
    {
        $this->normalize('1e+1')->shouldBe('10');
    }

    function it_normalizes_at_a_path()
    {
        $this->normalize('{"json":["spec"]}', "json/0")->shouldBe('"spec"');
    }

    function it_accept_a_json_value()
    {
        $this->normalize('1e+1')->shouldBe('10');
    }

    function it_normalizes_a_json_value()
    {
        $this->normalize('"json_spec"')->shouldBe('"json_spec"');
    }

    function it_generates_a_normalized_json_document()
    {
        $normalizedJson =
            '{
    "json": [
        "spec"
    ]
}';
        $this->generateNormalizedJson(['json'=>['spec']])->shouldBe(rtrim($normalizedJson));
    }

    public function it_should_exclude_keys()
    {
        $data = (object) array(
            'id' => 1,
            'collection' => array(
                (object) array(
                    'id' => 1,
                    'json' => 'spec'
                )
            )
        );

        $this->excludeKeys($data, array('id'))->shouldBeLike((object) array(
            'collection' => array(
                (object) array(
                    'json' => 'spec'
                )
            )
        ));
    }

}
