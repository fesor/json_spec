<?php

namespace JsonSpec;

class JsonHelper
{

    /**
     * Parses JSON string to PHP objects
     *
     * @param string $json
     * @return mixed
     */
    public function parse($json)
    {
        $data = json_decode($json);
        if (null === $data) {
            throw new \InvalidArgumentException(json_last_error_msg());
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
        try {
            $this->parse($json);
        } catch(\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * Normalizes JSON string
     *
     * @param string $json
     * @return string
     */
    public function normalize($json)
    {
        return $this->generateNormalizedJson($this->parse($json));
    }

    /**
     * Generate normalized JSON string from PHP objectмл
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
}
