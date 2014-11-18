<?php

namespace JsonSpec\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use JsonSpec\Behat\Context\Initializer\MemoryHelperAware;
use JsonSpec\Behat\JsonProvider\JsonHolder;
use JsonSpec\Helper\FileHelper;
use JsonSpec\Helper\JsonHelper;
use JsonSpec\Behat\Helper\MemoryHelper;
use JsonSpec\JsonSpecMatcher;

/**
 * Class JsonSpecContext
 * @package JsonSpec\Behat\Context
 */
class JsonSpecContext implements Context, JsonHolderAware, MemoryHelperAware
{

    /**
     * @var MemoryHelper
     */
    private $memoryHelper;

    /**
     * @var JsonHolder
     */
    private $jsonHolder;

    /**
     * @var JsonSpecMatcher
     */
    private $matcher;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @param JsonSpecMatcher $matcher
     * @param FileHelper      $fileHelper
     * @param JsonHelper      $jsonHelper
     */
    public function __construct(
        JsonSpecMatcher $matcher,
        FileHelper $fileHelper,
        JsonHelper $jsonHelper
    )
    {
        $this->matcher = $matcher;
        $this->fileHelper = $fileHelper;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @Then /^(?:I )?keep the (?:JSON|json)(?: response)?(?: at "(.*)")? as "(.*)"$/
     */
    public function keepJson($path, $key)
    {
        $json = $this->jsonHolder->getJson();
        $this->memoryHelper->memorize($key, $this->jsonHelper->normalize($json, $path));
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
        $this->checkEqualityInline($path, $isNegative, $this->fileHelper->loadJson($jsonFile));
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? be (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkEqualityInline($path, $isNegative, $json)
    {
        $matches = $this->matcher->isEqual(
            $this->memoryHelper->remember($this->jsonHolder->getJson()),
            $this->memoryHelper->remember($json),
            [JsonSpecMatcher::OPTION_PATH => $path]
        );
        if ($matches xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to be equal', $isNegative ? ' not' : ''));
        }
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
        $this->checkInclusionInline($path, $isNegative, $this->fileHelper->loadJson($jsonFile));
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? include (".*"|\-?\d+(?:\.\d+)?(?:[eE][\+\-]?\d+)?|\[.*\]|%?\{.*\}|true|false|null)$/
     */
    public function checkInclusionInline($path, $isNegative, $json)
    {
        $isNegative = !!$isNegative;
        $actual = $this->memoryHelper->remember($this->jsonHolder->getJson());
        if (
            $this->matcher->includes($actual, $this->memoryHelper->remember($json), [
                JsonSpecMatcher::OPTION_PATH => $path
            ]) xor !$isNegative
        ) {
            throw new \RuntimeException(sprintf('Expected JSON to be %s', $isNegative ?  'excluded' : 'included'));
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should have the following(:)$/
     */
    public function hasKeys($base, TableNode $table)
    {
        $actual = $this->jsonHelper->normalize(
            $this->memoryHelper->remember($this->jsonHolder->getJson()),
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
        $json = $this->memoryHelper->remember($this->jsonHolder->getJson());

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
        $json = $this->memoryHelper->remember($this->jsonHolder->getJson());
        if ($this->matcher->haveType($json, $type, [JsonSpecMatcher::OPTION_PATH => $path]) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to have type "%s"', $isNegative ?
                ' not' : '', $type));
        }
    }

    /**
     * @Then /^the (?:JSON|json)(?: response)?(?: at "(.*)")? should( not)? have (\d+)/
     */
    public function haveSize($path, $isNegative, $size)
    {
        $json = $this->memoryHelper->remember($this->jsonHolder->getJson());
        if ($this->matcher->haveSize($json, intval($size, 10), [JsonSpecMatcher::OPTION_PATH => $path]) xor !$isNegative) {
            throw new \RuntimeException(sprintf('Expected JSON%s to have size "%d"', $isNegative ?
                ' not' : '', $size));
        }
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


}
