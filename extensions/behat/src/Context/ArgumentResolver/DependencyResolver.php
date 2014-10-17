<?php

namespace JsonSpec\Behat\Context\ArgumentResolver;

use Behat\Behat\Context\Argument\ArgumentResolver;
use JsonSpec\Behat\Helper\MemoryHelper;
use JsonSpec\Helper\FileHelper;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\JsonSpecMatcher;
use ReflectionClass;

class DependencyResolver implements ArgumentResolver
{

    /**
     * @var array
     */
    private $dependencies;

    /**
     * @param JsonSpecMatcher $matcher
     * @param JsonHelper      $jsonHelper
     * @param FileHelper      $fileHelper
     */
    public function __construct(
        JsonSpecMatcher $matcher,
        JsonHelper $jsonHelper,
        FileHelper $fileHelper
    )
    {
        $this->dependencies = [
            $matcher,
            $jsonHelper,
            $fileHelper,
        ];
    }

    /**
     * @inheritdoc
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $constructor = $classReflection->getConstructor();
        if ($constructor !== null) {
            $parameters = $constructor->getParameters();
            foreach ($parameters as $parameter) {
                if (
                    null !== $parameter->getClass() &&
                    null !== ($dependency = $this->getDependency($parameter->getClass()->name))
                ) {
                    $arguments[$parameter->name] = $dependency;
                }
            }
        }

        return $arguments;
    }

    /**
     * @param string $className
     * @return object
     */
    private function getDependency($className)
    {
        foreach ($this->dependencies as $dependency) {
            if (is_a($dependency, $className, true)) {
                return $dependency;
            }
        }

        return null;
    }

}
