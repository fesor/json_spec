<?php

namespace JsonSpec\Helper;

use JsonSpec\Exception\MissingPathException;
use Seld\JsonLint\JsonParser;

/**
 * Class JsonHelper
 * @package JsonSpec\Helper
 *
 * Collection of utilities to work with JSON
 */
class JsonHelper
{

    /**
     * @var JsonParser
     */
    private $parser;

    /**
     * @param JsonParser $parser
     */
    public function __construct(JsonParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Returns parsed JSON data or its part by given path
     *
     * @param string $json
     * @param string|null $path
     * @return mixed
     */
    public function parse($json, $path = null)
    {
        $data = $this->parser->parse($json);

        if (!$path) {
            return $data;
        }

        return $this->getAtPath($data, $path);
    }

    /**
     * Checks is given JSON string is valid or not
     *
     * @param string $json
     * @return boolean
     */
    public function isValid($json)
    {
        return null === $this->parser->lint($json);
    }

    /**
     * @param $json
     * @param null $path
     * @return string
     */
    public function normalize($json, $path = null)
    {
        return $this->generateNormalizedJson($this->parse($json, $path));
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function generateNormalizedJson($data)
    {
        return rtrim(json_encode(
            $this->sortObjectKeys($data),
            JSON_PRETTY_PRINT
        ));
    }

    /**
     * Recursively removes specific keys from
     *
     * @param $data
     * @param array|null excludedKeys
     * @return mixed
     */
    public function excludeKeys($data, array $excludedKeys = array())
    {

        if (is_object($data)) {
            $object = new \stdClass();
            foreach(get_object_vars($data) as $key => $value) {
                if (in_array($key, $excludedKeys)) continue;
                $object->$key = $this->excludeKeys($value, $excludedKeys);
            }

            return $object;
        }

        if (is_array($data)) {

            return array_map(function ($data) use ($excludedKeys) {
                return $this->excludeKeys($data, $excludedKeys);
            }, $data);
        }

        return $data;
    }

    /**
     * Get data by given JSON path
     *
     * @param mixed $data
     * @param string $path
     * @return mixed
     */
    private function getAtPath($data, $path)
    {
        $pathSegments = explode('/', trim($path, '/'));
        foreach ($pathSegments as $key) {

            if ($data instanceof \stdClass && property_exists($data, $key)) {
                $data = $data->$key;
            } else if (is_array($data) && preg_match('/^\d+$/', $key) && array_key_exists(intval($key), $data)) {
                $data = $data[$key];
            } else {
                throw new MissingPathException($path);
            }
        }

        return $data;
    }

    private function sortObjectKeys($data)
    {
        if (is_array($data)) {
            return array_map(function ($data) {
                return $this->sortObjectKeys($data);
            }, $data);
        }

        if (is_object($data)) {
            $values = get_object_vars($data);
            ksort($values);

            return (object) $values;
        }

        return $data;
    }
}
