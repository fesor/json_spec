<?php

namespace JsonSpec\PhpSpec\Runner\Maintainer;

use JsonSpec\Helper\JsonHelper;
use \JsonSpec\PhpSpec\Matcher;
use \JsonSpec\Matcher as BaseMatcher;
use JsonSpec\MatcherOptionsFactory;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;

class JsonSpecMaintainer implements MaintainerInterface
{

    /**
     * @var JsonHelper
     */
    private $helper;

    /**
     * @var MatcherOptionsFactory
     */
    private $optionsFactory;

    /**
     * @param JsonHelper            $helper
     * @param MatcherOptionsFactory $optionsFactory
     */
    public function __construct(JsonHelper $helper, MatcherOptionsFactory $optionsFactory)
    {
        $this->helper = $helper;
        $this->optionsFactory = $optionsFactory;
    }

    /**
     * @param ExampleNode $example
     *
     * @return boolean
     */
    public function supports(ExampleNode $example)
    {
        return true;
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        // add matchers
        $matchers->add(new Matcher\BeJsonEqualMatcher($this->createMatcher('JsonSpec\\Matcher\\BeJsonEqualMatcher')));
        $matchers->add(new Matcher\JsonHaveSizeMatcher($this->createMatcher('JsonSpec\\Matcher\\JsonHaveSizeMatcher')));
        $matchers->add(new Matcher\JsonHaveTypeMatcher($this->createMatcher('JsonSpec\\Matcher\\JsonHaveTypeMatcher')));
        $matchers->add(new Matcher\JsonIncludesMatcher($this->createMatcher('JsonSpec\\Matcher\\JsonIncludesMatcher')));
        $matchers->add(new Matcher\JsonHavePathMatcher($this->createMatcher('JsonSpec\\Matcher\\JsonHavePathMatcher')));
    }

    private function createMatcher($className)
    {
        $matcher = new $className($this->helper);
        if ($matcher instanceof BaseMatcher\Matcher) {
            $matcher->setOptions($this->optionsFactory->createOptions());
        }

        return $matcher;
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 50;
    }

}
