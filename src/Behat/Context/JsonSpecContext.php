<?php

namespace JsonSpec\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use JsonSpec\Behat\Provider\JsonProvider;
use JsonSpec\Helper\MemoryHelper;
use JsonSpec\Matcher;

class JsonSpecContext implements Context
{

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

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
     * @var Matcher\JsonHaveTypeMatcher
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
     */
    public function __construct(
        Matcher\BeJsonEqualMatcher $beJsonEqual,
        Matcher\JsonHavePathMatcher $haveJsonPath,
        Matcher\JsonHaveTypeMatcher $haveJsonSize,
        Matcher\JsonHaveTypeMatcher $haveJsonType,
        Matcher\JsonIncludesMatcher  $includeJson,
        MemoryHelper $memoryHelper,
        JsonProvider $jsonProvider
    )
    {
        $this->beJsonEqual = $beJsonEqual;
        $this->haveJsonPath = $haveJsonPath;
        $this->haveJsonSize = $haveJsonSize;
        $this->haveJsonType = $haveJsonType;
        $this->includeJson = $includeJson;
        $this->memoryHelper = $memoryHelper;
        $this->jsonProvider = $jsonProvider;
    }

    /**
     * @Then /^(?:I )?keep the (?:JSON|json)(?: response)?(?: at "(.*)")? as "(.*)"$/
     */
    public function keepJson($path, $key)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be:$/
     */
    public function checkEquality($path = null, $isNegative = null, PyStringNode $json = null)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkEqualityInline($path, $isNegative, $json)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include(:)$/
     */
    public function checkInclusion($path, $isNegative, $json)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkInclusionInline($path, $isNegative, $json)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should have the following(:)$/
     */
    public function hasKeys($base, TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)? should( not)? have "(.*)"$/
     */
    public function hasKeysInline($isNegative, $path)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be an? (.*)$/
     */
    public function haveType($path, $isNegative, $type)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? have (\d+)/
     */
    public function haveSize($path, $isNegative, $size)
    {
        throw new PendingException();
    }

}
