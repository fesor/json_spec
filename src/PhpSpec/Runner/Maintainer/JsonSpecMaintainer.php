<?php

namespace JsonSpec\PhpSpec\Runner\Maintainer;

use JsonSpec\Helper\FileHelper;
use JsonSpec\JsonSpecMatcher;
use \JsonSpec\PhpSpec\Matcher;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;

class JsonSpecMaintainer implements MaintainerInterface
{

    /**
     * @var JsonSpecMatcher
     */
    private $matcher;

    /**
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @param JsonSpecMatcher $matcher
     */
    public function __construct(JsonSpecMatcher $matcher, FileHelper $fileHelper)
    {
        $this->matcher = $matcher;
        $this->fileHelper = $fileHelper;
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
        $matchers->add($this->prepareMatcher(new Matcher\BeJsonEqualMatcher($this->matcher)));
        $matchers->add($this->prepareMatcher(new Matcher\JsonHaveSizeMatcher($this->matcher)));
        $matchers->add($this->prepareMatcher(new Matcher\JsonHaveTypeMatcher($this->matcher)));
        $matchers->add($this->prepareMatcher(new Matcher\JsonIncludesMatcher($this->matcher)));
        $matchers->add($this->prepareMatcher(new Matcher\JsonHavePathMatcher($this->matcher)));
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

    private function prepareMatcher($matcher)
    {
        if ($matcher instanceof FileHelperAware) {
            $matcher->setFileHelper($this->fileHelper);
        }

        return $matcher;
    }

}
