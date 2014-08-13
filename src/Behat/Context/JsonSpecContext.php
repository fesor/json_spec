<?php

namespace JsonSpec\Behat\Context;

use Behat\Behat\Context\ExtendedContextInterface;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use JsonSpec\Behat\Provider\JsonProvider;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\Helper\MemoryHelper;
use JsonSpec\JsonSpecMatcher;

/**
 * Class JsonSpecContext
 * @package JsonSpec\Behat\Context
 */
class JsonSpecContext implements ExtendedContextInterface
{

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @var JsonProvider
     */
    private $jsonProvider;

    /**
     * @var JsonSpecMatcher
     */
    private $matcher;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var ExtendedContextInterface
     */
    private $mainContext;

    /**
     * @var ExtendedContextInterface[]
     */
    private $subContexts;

    public function __construct()
    {
        $this->subContexts = array();
    }

    /**
     * @param JsonProvider    $jsonProvider
     * @param JsonSpecMatcher $matcher
     * @param MemoryHelper    $memoryHelper
     * @param JsonHelper      $jsonHelper
     */
    public function init(
        JsonProvider $jsonProvider,
        JsonSpecMatcher $matcher,
        MemoryHelper $memoryHelper,
        JsonHelper $jsonHelper
    )
    {
        $this->jsonProvider = $jsonProvider;
        $this->matcher = $matcher;
        $this->memoryHelper = $memoryHelper;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @Then /^(?:I )?keep the (?:JSON|json)(?: response)?(?: at "(.*)")? as "(.*)"$/
     */
    public function keepJson($path, $key)
    {
        $json = $this->jsonProvider->getJson();
        $this->memoryHelper->memorize($key, $this->jsonHelper->normalize($json, $path));
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be(:)$/
     */
    public function checkEquality($path = null, $isNegative = null, PyStringNode $json = null)
    {
        $this->checkEqualityInline($path, $isNegative, $json->getRaw());
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkEqualityInline($path, $isNegative, $json)
    {
        $options = $this->matcher->getOptions();
        $options->atPath($path);
        $matches = $this->matcher->isEqual(
            $this->memoryHelper->remember($this->jsonProvider->getJson()),
            $this->memoryHelper->remember($json)
        );
        if ($matches xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to be equal', $isNegative ? ' not' : ''));
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include(:)$/
     */
    public function checkInclusion($path, $isNegative, $json)
    {
        $this->checkInclusionInline($path, $isNegative, $json);
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkInclusionInline($path, $isNegative, $json)
    {
        $actual = $this->memoryHelper->remember($this->jsonProvider->getJson());
        $this->matcher->getOptions()->atPath($path);
        if ($this->matcher->includes($actual, $this->memoryHelper->remember($json)) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON to be %s', $isNegative ? 'included' : 'excluded'));
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should have the following(:)$/
     */
    public function hasKeys($base, TableNode $table)
    {
        $actual = $this->jsonHelper->normalize(
            $this->memoryHelper->remember($this->jsonProvider->getJson()),
            $base
        );
        foreach ($table->getRows() as $row) {
            if (count ($row) == 2) {
                $this->checkEqualityInline(ltrim($base . '/' .$row[0], '/'), false, $row[1]);
            } else {
                if (!$this->matcher->havePath($actual, $row[0])) {
                    throw new \RuntimeException(sprintf('Expected JSON to have path "%s"', $row[0]));
                }
            }
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)? should( not)? have "(.*)"$/
     */
    public function hasKeysInline($isNegative, $path)
    {
        $json = $this->memoryHelper->remember($this->jsonProvider->getJson());
        if ($this->matcher->havePath($json, $path) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to have path "%s"', $isNegative ?
                ' not' : '', $path));
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be an? (.*)$/
     */
    public function haveType($path, $isNegative, $type)
    {
        $json = $this->memoryHelper->remember($this->jsonProvider->getJson());
        $this->matcher->getOptions()->atPath($path);
        if ($this->matcher->haveType($json, $type) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to have type "%s"', $isNegative ?
                ' not' : '', $type));
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? have (\d+)/
     */
    public function haveSize($path, $isNegative, $size)
    {
        $json = $this->memoryHelper->remember($this->jsonProvider->getJson());
        $this->matcher->getOptions()->atPath($path);
        if ($this->matcher->haveSize($json, intval($size, 10)) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to have size "%d"', $isNegative ?
                ' not' : '', $size));
        }
    }

    /**
     * @inheritdoc
     */
    public function setParentContext(ExtendedContextInterface $parentContext)
    {
        $this->mainContext = $parentContext;
    }

    /**
     * @inheritdoc
     */
    public function getMainContext()
    {
        return $this->mainContext;
    }

    /**
     * @inheritdoc
     */
    public function getSubcontext($alias)
    {
        if (!isset($this->subContexts[$alias])) {
            throw new \InvalidArgumentException(sprintf('Undefined context with alias \'%s\'', $alias));
        }

        return $this->subContexts[$alias];
    }

    /**
     * @inheritdoc
     */
    public function getSubcontexts()
    {
        return $this->subContexts;
    }

    /**
     * @inheritdoc
     */
    public function getSubcontextByClassName($className)
    {
        foreach ($this->subContexts as $context) {
            if ($context instanceof $className) {
                return $context;
            }
        }

        throw new \InvalidArgumentException(sprintf('Undefined context with class name \'%s\'', $alias));
    }

}
