<?php

namespace spec\JsonSpec\PhpSpec\Runner\Maintainer;

use JsonSpec\Helper\FileHelper;
use JsonSpec\JsonSpecMatcher;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use JsonSpec\PhpSpec\Matcher\JsonHaveSizeMatcher as Matcher;
use JsonSpec\PhpSpec\Matcher\BeJsonEqualMatcher as FileHelperAwareMatcher;
use Prophecy\Argument;

class JsonSpecMaintainerSpec extends ObjectBehavior
{

    public function let(JsonSpecMatcher $matcher, FileHelper $fileHelper)
    {
        $this->beConstructedWith($matcher, $fileHelper);
    }

    public function it_provides_maintainer_prority()
    {
        $this->getPriority()->shouldBe(50);
    }

    public function it_supports_every_example_node(ExampleNode $example)
    {
        $this->supports($example)->shouldBe(true);
    }

    public function it_adds_json_spec_matchers_to_matchers_collection(
        ExampleNode $example, SpecificationInterface $context,
        MatcherManager $matchers, CollaboratorManager $collaborators
    ) {
        $matchers->add(Argument::type('JsonSpec\PhpSpec\Matcher\BeJsonEqualMatcher'))->willReturn(null);
        $matchers->add(Argument::type('JsonSpec\PhpSpec\Matcher\JsonHaveSizeMatcher'))->willReturn(null);
        $matchers->add(Argument::type('JsonSpec\PhpSpec\Matcher\JsonHaveTypeMatcher'))->willReturn(null);
        $matchers->add(Argument::type('JsonSpec\PhpSpec\Matcher\JsonIncludesMatcher'))->willReturn(null);
        $matchers->add(Argument::type('JsonSpec\PhpSpec\Matcher\JsonHavePathMatcher'))->willReturn(null);

        $this->shouldNotThrow()->duringPrepare($example, $context, $matchers, $collaborators);
    }
}
