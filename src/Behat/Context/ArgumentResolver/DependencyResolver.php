<?php

namespace JsonSpec\Behat\Context\ArgumentResolver;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Fesor\JsonMatcher\Helper\JsonHelper;
use Fesor\JsonMatcher\JsonMatcherFactory;
use JsonSpec\JsonLoader;
use ReflectionClass;

/**
 * Class DependencyResolver
 * @package JsonSpec\Behat\Context\ArgumentResolver
 */
class DependencyResolver implements ArgumentResolver
{

    /**
     * @var array
     */
    private $dependencies;

    /**
     * @param JsonMatcherFactory $matcherFactory
     * @param JsonLoader $jsonLoader
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        JsonMatcherFactory $matcherFactory,
        JsonLoader $jsonLoader,
        JsonHelper $jsonHelper
    )
    {
        $this->dependencies = [
            $matcherFactory,
            $jsonLoader,
            $jsonHelper
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
