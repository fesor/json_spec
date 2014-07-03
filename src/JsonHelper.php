<?php

namespace JsonSpec;

use JsonSpec\Exception\JsonSpecException;
use JsonSpec\Exception\MissingPathException;
use JsonSpec\Exception\NotIncludedException;
use Seld\JsonLint\JsonParser;

class JsonHelper
{

    /**
     * @var JsonParser
     */
    private $parser;

    /**
     * todo: move JsonParser initialization from constructor
     */
    public function __construct()
    {
        $this->parser = new JsonParser();
    }

    /**
     * Parses JSON string to PHP objects
     *
     * @param string $json
     * @param string|null $path
     * @return mixed
     */
    public function parse($json, $path = null)
    {
        $data = $this->parser->parse($json);

        if ($path) {
            return $this->getAtPath($data, $path);
        }

        return $data;
    }

    /**
     * Validates JSON string
     *
     * @param string $json
     * @return bool
     */
    public function isValid($json)
    {
        return null === $this->parser->lint($json);
    }

    /**
     * Normalizes JSON string
     *
     * @param string $json
     * @param string|null $path
     * @return string
     */
    public function normalize($json, $path = null)
    {
        return $this->generateNormalizedJson($this->parse($json, $path));
    }

    /**
     * Generate normalized JSON string from PHP object
     *
     * @param mixed $data
     * @return string
     */
    public function generateNormalizedJson($data)
    {
        return rtrim(json_encode(
            $data,
            JSON_PRETTY_PRINT
        ));
    }

    public function isIncludes($json, $expected, $path = null)
    {
        $expected = $this->normalize($expected);
        $data = (array) $this->parse($json, $path);
        foreach ($data as $value) {
            if ($this->generateNormalizedJson($value) === $expected) {
                return true;
            }
        }

        throw new NotIncludedException($this->normalize($expected), $path);
    }

    private function getAtPath($json, $path)
    {
        $pathSegments = explode('/', trim($path, '/'));
        foreach ($pathSegments as $key) {

            if ($json instanceof \stdClass && isset($json->$key)) {
                $json = $json->$key;
            } else if (is_array($json) && preg_match('/^\d+$/', $key) && isset($json[intval($key)])) {
                $json = $json[$key];
            } else {
                throw new MissingPathException($path);
            }
        }

        return $json;
    }
}
