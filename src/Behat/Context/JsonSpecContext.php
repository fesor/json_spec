<?php

namespace JsonSpec\Behat\Context;

use Behat\Behat\Context\Context;
use Fesor\JsonMatcher\Helper\JsonHelper;
use \JsonSpec\Behat\Context\Traits\JsonMatcherAwareTrait;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Fesor\JsonMatcher\JsonMatcher;
use JsonSpec\Behat\JsonProvider\JsonHolder;
use JsonSpec\Behat\Helper\MemoryHelper;
use JsonSpec\JsonLoader;

/**
 * Class JsonSpecContext
 * @package JsonSpec\Behat\Context
 */
class JsonSpecContext implements Context, JsonHolderAware, MemoryHelperAware, JsonMatcherAware
{

    use JsonMatcherAwareTrait;

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @var JsonHolder
     */
    private $jsonHolder;

    /**
     * @var JsonLoader
     */
    private $jsonLoader;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @param JsonLoader $jsonLoader
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        JsonLoader $jsonLoader,
        JsonHelper $jsonHelper
    )
    {
        $this->jsonLoader = $jsonLoader;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @Then /^(?:I )?keep the (?:JSON|json)(?: response)?(?: at "(.*)")? as "(.*)"$/
     */
    public function keepJson($path, $key)
    {
        $json = $this->jsonHolder->getJson();
        $this->memoryHelper->memorize($key, $this->jsonHelper->normalize($json, $this->normalizePath($path)));
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be(:)$/
     */
    public function checkEquality($path, $isNegative, PyStringNode $json)
    {
        $this->checkEqualityInline($path, $isNegative, $json->getRaw());
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be file "(.+)"/
     */
    public function checkEqualityWithFileContents($path = null, $isNegative = null, $jsonFile)
    {
        $this->checkEqualityInline($path, $isNegative, $this->jsonLoader->loadJson($jsonFile));
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkEqualityInline($path, $isNegative, $json)
    {
        $this
            ->json($this->rememberJson())
            ->equal($this->memoryHelper->remember($json), [
                'at' => $this->normalizePath($path),
                JsonMatcher::OPTION_NEGATIVE => !!$isNegative
            ])
        ;
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include(:)$/
     */
    public function checkInclusion($path, $isNegative, PyStringNode $json)
    {
        $this->checkInclusionInline($path, $isNegative, $json->getRaw());
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include file "(.+)"$/
     */
    public function checkInclusionOfFile($path, $isNegative, $jsonFile)
    {
        $this->checkInclusionInline($path, $isNegative, $this->jsonLoader->loadJson($jsonFile));
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkInclusionInline($path, $isNegative, $json)
    {
        $this
            ->json($this->rememberJson())
            ->includes($this->rememberJson($json), [
                'at' => $this->normalizePath($path),
                JsonMatcher::OPTION_NEGATIVE => !!$isNegative
            ])
        ;
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should have the following(:)$/
     */
    public function hasKeys($base, TableNode $table)
    {
        $matcher = $this->json($this->rememberJson());

        foreach ($table->getRows() as $row) {
            $path = ltrim("$base/{$row[0]}");
            if (count ($row) == 2) {
                $matcher->equal($this->rememberJson($row[1]), ['at' => $this->normalizePath($path)]);
            } else {
                $matcher->hasPath($path);
            }
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)? should( not)? have "(.*)"$/
     */
    public function hasKeysInline($isNegative, $path)
    {
        $this
            ->json($this->rememberJson())
            ->hasPath($path, [
                JsonMatcher::OPTION_NEGATIVE => !!$isNegative
            ])
        ;
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be an? (.*)$/
     */
    public function haveType($path, $isNegative, $type)
    {
        $this
            ->json($this->rememberJson())
            ->hasType($type, [
                'at' => $this->normalizePath($path),
                JsonMatcher::OPTION_NEGATIVE => !!$isNegative
            ])
        ;
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? have (\d+)/
     */
    public function haveSize($path, $isNegative, $size)
    {
        $this
            ->json($this->rememberJson())
            ->hasSize(intval($size, 10), [
                'at' => $this->normalizePath($path),
                JsonMatcher::OPTION_NEGATIVE => !!$isNegative
            ])
        ;
    }

    /**
     * @Then print last JSON response
     */
    public function printLastJsonResponse()
    {
        echo (string) $this->jsonHolder->getJson();
    }

    /**
     * @inheritdoc
     */
    public function setJsonHolder(JsonHolder $holder)
    {
        $this->jsonHolder = $holder;
    }

    /**
     * @inheritdoc
     */
    public function setMemoryHelper(MemoryHelper $memoryHelper)
    {
        $this->memoryHelper = $memoryHelper;
    }

    /**
     * @return string
     */
    private function rememberJson($json = null)
    {
        if (null === $json) {
            $json = $this->jsonHolder->getJson();
        }

        return $this->memoryHelper->remember($json);
    }

    private function normalizePath($path)
    {
        if (0 === strlen(ltrim($path, '/'))) {
            return null;
        }

        return $path;
    }

}
