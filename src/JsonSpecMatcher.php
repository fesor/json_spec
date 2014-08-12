<?php

namespace JsonSpec;

use JsonSpec\Exception\MissingPathException;
use JsonSpec\Helper\JsonHelper;

class JsonSpecMatcher
{

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var MatcherOptionsFactory
     */
    private $optionsFactory;

    /**
     * @var MatcherOptions
     */
    private $currentOptions;

    /**
     * @param JsonHelper $jsonHelper
     * @param MatcherOptionsFactory $optionsFactory
     */
    public function __construct(JsonHelper $jsonHelper, MatcherOptionsFactory $optionsFactory)
    {
        $this->jsonHelper = $jsonHelper;
        $this->optionsFactory = $optionsFactory;
        $this->currentOptions = $this->optionsFactory->createOptions();
    }

    /**
     * @return MatcherOptions
     */
    public function getOptions()
    {
        return $this->currentOptions = $this->optionsFactory->createOptions();
    }

    /**
     * @param string $actual
     * @param string $expected
     * @return bool
     */
    public function isEqual($actual, $expected)
    {
        $actual = $this->scrub($actual, $this->currentOptions->getPath());
        $expected = $this->scrub($expected);

        return $actual === $expected;
    }

    /**
     * @param string $json
     * @param string|null $path
     * @return bool
     */
    public function havePath($json, $path)
    {
        // get base path
        $basePath = $this->currentOptions->getPath();
        $path = ltrim($basePath . '/' . $path, '/');

        try {
            $this->jsonHelper->parse($json, $path);
        } catch (MissingPathException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $json
     * @param integer $expectedSize
     * @return bool
     */
    public function haveSize($json, $expectedSize)
    {
        $data = $this->jsonHelper->parse($json, $this->currentOptions->getPath());

        if (!is_array($data) && !is_object($data)) {
            return false;
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        return $expectedSize === count($data);
    }

    /**
     * @param string $json
     * @param string $type
     * @return bool
     */
    public function haveType($json, $type)
    {
        $data = $this->jsonHelper->parse($json, $this->currentOptions->getPath());

        if ($type == 'float') {
            $type = 'double';
        }

        return gettype($data) === $type;
    }

    /**
     * @param string $json
     * @param string $expected
     * @return bool
     */
    public function includes($json, $expected)
    {
        $actual = $this->scrub($json, $this->currentOptions->getPath());
        $expected = $this->scrub($expected);

        return $this->isIncludes($this->jsonHelper->parse($actual), $expected);
    }

    /**
     * @param $json
     * @param  null   $path
     * @return string
     */
    private function scrub($json, $path = null)
    {
        return $this->jsonHelper->generateNormalizedJson(
            $this->jsonHelper->excludeKeys(
                $this->jsonHelper->parse($json, $path),
                $this->currentOptions->getExcludedKeys()
            )
        );
    }

    /**
     * @param $data
     * @param $json
     * @return bool
     */
    private function isIncludes($data, $json)
    {

        $normalizedData = $this->jsonHelper->generateNormalizedJson($data);
        if (!is_object($data) && !is_array($data)) {
            return  $normalizedData === $json;
        }

        if ($normalizedData === $json) {
            return true;
        }

        if (is_object($data)) {
            if ($this->jsonHelper->generateNormalizedJson($data) === $json) {
                return true;
            }

            foreach (get_object_vars($data) as $value) {
                if ($this->isIncludes($value, $json)) {
                    return true;
                }
            }

            return false;
        }

        if (is_array($data)) {
            foreach ($data as $value) {
                if ($this->isIncludes($value, $json)) {
                    return true;
                }
            }
        }

        return false;
    }

}
