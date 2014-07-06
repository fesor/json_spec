<?php

namespace spec\JsonSpec\PhpSpec\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BeJsonEqualMatcherSpec extends ObjectBehavior
{
    function it_supports_delayed_execution()
    {
        $this->shouldImplement('JsonSpec\PhpSpec\Matcher\DelayedMatcherInterface');
    }

    function it_responds_to_be_json_equal()
    {
        $this->supports('beJsonEqual', '', array(''))->shouldReturn(true);
    }

    function it_should_return_configuration_chainer()
    {
        $this->promise()->shouldHaveType('JsonSpec\Matcher\MatcherOptions');
    }

    function it_matches_identical_json()
    {
        $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', '{"json":"spec"}', array('{"json":"spec"}'));
    }

    function it_matches_differently_formatted_json()
    {
        $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', '{"json": "spec"}', array('{"json":"spec"}'));
    }

    function it_matches_out_of_order_hashes()
    {
        $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', '{"laser":"lemon","json":"spec"}', array('{"json":"spec","laser":"lemon"}'));
    }

    function it_doesnt_match_out_of_order_arrays()
    {
        $this->shouldNotThrow()->duringNegativeMatch('beJsonEqual', '["json","spec"]', array('["spec","json"]'));
    }

    function it_matches_valid_json_values_yet_invalid_json_documents()
    {
        $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', '"json_spec"', array('"json_spec"'));
    }

    function it_matches_at_a_path()
    {
        $this->shouldNotThrow()->duringPositiveMatch('beJsonEqual', '{"json":["spec"]}', array('"spec"'));
    }

}
