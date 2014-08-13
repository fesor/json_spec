<?php

namespace JsonSpec\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use JsonSpec\Behat\Provider\JsonProvider;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\Helper\MemoryHelper;
use JsonSpec\Matcher;

class JsonSpecContext implements Context
{

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var Matcher\BeJsonEqualMatcher
     */
    private $beJsonEqual;

    /**
     * @var Matcher\JsonIncludesMatcher
     */
    private $includeJson;

    /**
     * @var Matcher\JsonHavePathMatcher
     */
    private $haveJsonPath;

    /**
     * @var Matcher\JsonHaveSizeMatcher
     */
    private $haveJsonSize;

    /**
     * @var Matcher\JsonHaveTypeMatcher
     */
    private $haveJsonType;

    /**
     * @var JsonProvider
     */
    private $jsonProvider;

    /**
     * @param Matcher\BeJsonEqualMatcher  $beJsonEqual
     * @param Matcher\JsonHavePathMatcher $haveJsonPath
     * @param Matcher\JsonHaveTypeMatcher $haveJsonSize
     * @param Matcher\JsonHaveTypeMatcher $haveJsonType
     * @param Matcher\JsonIncludesMatcher $includeJson
     * @param MemoryHelper                $memoryHelper
     *                                                  fixme: remove contructor...
     */
    public function __construct(
        Matcher\BeJsonEqualMatcher $beJsonEqual,
        Matcher\JsonHavePathMatcher $haveJsonPath,
        Matcher\JsonHaveSizeMatcher $haveJsonSize,
        Matcher\JsonHaveTypeMatcher $haveJsonType,
        Matcher\JsonIncludesMatcher  $includeJson,
        MemoryHelper $memoryHelper,
        JsonHelper $jsonHelper,
        JsonProvider $jsonProvider
    )
    {
        $this->beJsonEqual = $beJsonEqual;
        $this->haveJsonPath = $haveJsonPath;
        $this->haveJsonSize = $haveJsonSize;
        $this->haveJsonType = $haveJsonType;
        $this->includeJson = $includeJson;
        $this->memoryHelper = $memoryHelper;
        $this->jsonHelper = $jsonHelper;
        $this->jsonProvider = $jsonProvider;
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
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be:$/
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
        $options = $this->beJsonEqual->getOptions();
        $options->atPath($path);
        $matches = $this->beJsonEqual->match(
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
        $this->includeJson->getOptions()->atPath($path);
        if ($this->includeJson->match($actual, $this->memoryHelper->remember($json)) xor !$isNegative) {
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
                if (!$this->haveJsonPath->match($actual, $row[0])) {
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
        if ($this->haveJsonPath->match($json, $path) xor !$isNegative) {
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
        $this->haveJsonType->getOptions()->atPath($path);
        if ($this->haveJsonType->match($json, $type) xor !$isNegative) {
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
        $this->haveJsonSize->getOptions()->atPath($path);
        if ($this->haveJsonSize->match($json, intval($size, 10)) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to have size "%d"', $isNegative ?
                ' not' : '', $size));
        }
    }

}
