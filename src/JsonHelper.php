<?php

namespace JsonSpec;

class JsonHelper
{

    public function parse($json)
    {
        $data = json_decode($json);
        if (null === $data) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $data;
    }

    public function isValid($json)
    {
        try {
            $this->parse($json);
        } catch(\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    public function normalize($json)
    {
        return $this->generateNormalizedJson($this->parse($json));
    }

    public function generateNormalizedJson($data)
    {
        return rtrim(json_encode(
            $data,
            JSON_PRETTY_PRINT
        ));
    }
}
