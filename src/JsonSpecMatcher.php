<?php

namespace JsonSpec;

use JsonSpec\Exception\MissingPathException;
use JsonSpec\Helper\JsonHelper;

class JsonSpecMatcher
{

    const OPTION_PATH = 'at';
    const OPTION_EXCLUDE_KEYS = 'excluding';
    const OPTION_INCLUDE_KEYS = 'including';

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var array
     */
    private $excludeKeys;

    /**
     * @param JsonHelper            $jsonHelper
     * @param array $excludeKeys
     */
    public function __construct(JsonHelper $jsonHelper, array $excludeKeys = [])
    {
        $this->jsonHelper = $jsonHelper;
        $this->excludeKeys = $excludeKeys;
    }

    /**
     * @param  string $actual
     * @param  string $expected
     * @param  array $options
     * @return bool
     */
    public function isEqual($actual, $expected, array $options = [])
    {
        $actual = $this->scrub($actual, $options);
        $expected = $this->scrub($expected, array_diff_key(
            // we should pass all options except `path`
            $options, [static::OPTION_PATH => null]
        ));

        return $actual === $expected;
    }

    /**
     * @param  string      $json
     * @param  string|null $path
     * @param  array $options
     * @return bool
     */
    public function havePath($json, $path, array $options = [])
    {
        // get base path
        $basePath = $this->getPath($options);
        $path = ltrim($basePath . '/' . $path, '/');

        try {
            $this->jsonHelper->parse($json, $path);
        } catch (MissingPathException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param  string  $json
     * @param  integer $expectedSize
     * @param  array $options
     * @return bool
     */
    public function haveSize($json, $expectedSize, array $options = [])
    {
        $data = $this->jsonHelper->parse($json, $this->getPath($options));

        if (!is_array($data) && !is_object($data)) {
            return false;
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        return $expectedSize === count($data);
    }

    /**
     * @param  string $json
     * @param  string $type
     * @return bool
     */
    public function haveType($json, $type, array $options = [])
    {
        $data = $this->jsonHelper->parse($json, $this->getPath($options));

        if ($type == 'float') {
            $type = 'double';
        }

        return gettype($data) === $type;
    }

    /**
     * @param  string $json
     * @param  string $expected
     * @return bool
     */
    public function includes($json, $expected, array $options = [])
    {
        $actual = $this->scrub($json, $options);
        $expected = $this->scrub($expected);

        return $this->isIncludes($this->jsonHelper->parse($actual), $expected);
    }

    /**
     * @param $json
     * @param  array $options
     * @return string
     */
    private function scrub($json, array $options = [])
    {
        return $this->jsonHelper->generateNormalizedJson(
            $this->jsonHelper->excludeKeys(
                $this->jsonHelper->parse($json, $this->getPath($options)),
                $this->getExcludedKeys($options)
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

        $parsedJson = $this->jsonHelper->parse($json);
        $normalizedData = $this->jsonHelper->generateNormalizedJson($data);
        if (!is_object($data) && !is_array($data)) {
            if (is_string($data) && is_string($parsedJson)) {
                return false !== strpos($data, $parsedJson);
            }

            return $normalizedData === $json;
        }

        if ($normalizedData === $json) {
            return true;
        }

        if (is_object($data)) {
            if ($this->jsonHelper->generateNormalizedJson($data) === $json) {
                return true;
            }

            $data = get_object_vars($data);
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

    private function getPath(array $options)
    {
        return $this->option($options, static::OPTION_PATH, null);
    }

    private function getExcludedKeys(array $options)
    {
        $excludedKeys = $this->option($options, static::OPTION_EXCLUDE_KEYS, $this->excludeKeys);
        $includedKeys = $this->option($options, static::OPTION_INCLUDE_KEYS, []);

        return array_diff($excludedKeys, $includedKeys);
    }

    private function option(array $options, $optionName, $default = null) {

        return array_key_exists($optionName, $options) ?
            $options[$optionName] : $default
        ;
    }

}
