<?php

namespace JsonSpec\Behat\Context\Argument;

use \Behat\Behat\Context\Argument\ArgumentResolver;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\Helper\MemoryHelper;
use JsonSpec\Matcher;
use JsonSpec\MatcherOptionsFactory;
use ReflectionClass;

class JsonSpecArgumentResolver implements ArgumentResolver
{

    /**
     * @var MatcherOptionsFactory
     */
    private $optionsFactory;

    /**
     * @var JsonHelper
     */
    private $helper;

    /**
     * @var MemoryHelper
     */
    private $memory;

    /**
     * @var array of available matchers
     */
    private $matchers = array(
        'JsonSpec\\Matcher\\BeJsonEqualMatcher',
        'JsonSpec\\Matcher\\JsonIncludesMatcher',
        'JsonSpec\\Matcher\\JsonHaveSizeMatcher',
        'JsonSpec\\Matcher\\JsonHaveTypeMatcher',
        'JsonSpec\\Matcher\\JsonHavePathMatcher',
    );

    /**
     * @param MatcherOptionsFactory $optionsFactory
     * @param MemoryHelper          $memory
     * @param JsonHelper            $helper
     */
    public function __construct(MatcherOptionsFactory $optionsFactory, MemoryHelper $memory, JsonHelper $helper)
    {
        $this->optionsFactory = $optionsFactory;
        $this->memory = $memory;
        $this->helper = $helper;
    }

    /**
     * Resolves context constructor arguments.
     *
     * @param ReflectionClass $classReflection
     * @param mixed[]         $arguments
     *
     * @return mixed[]
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        if ($classReflection->getName() !== 'JsonSpec\Behat\Context\JsonSpecContext') {
            return array();
        }

        return $this->getArguments($classReflection->getConstructor()->getParameters());
    }

    /**
     * @param  \ReflectionParameter[] $arguments
     * @return array
     */
    private function getArguments($arguments)
    {
        $types = array_map(function (\ReflectionParameter $parameter) {
            return $parameter->getClass()->name;
        }, $arguments);

        $matchers = $this->createMatchers(array_intersect($types, $this->matchers));
        $matchers[] = $this->memory;

        return $matchers;
    }

    /**
     * @param $matchers
     * @return array
     */
    private function createMatchers($matchers)
    {
        $self = $this;

        return array_map(function ($className) use ($self) {
            return new $className($self->helper, $self->optionsFactory->createOptions());
        }, $matchers);
    }

}
