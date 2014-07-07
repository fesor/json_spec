<?php

namespace JsonSpec\Matcher;

class JsonIncludesMatcher extends Matcher
{

    public function match($actual, $expected)
    {
        $actual = $this->scrub($actual, $this->options->getPath());
        $expected = $this->scrub($expected);

        return $this->isIncludes($this->helper->parse($actual), $expected);
    }

    /**
     * @param $data
     * @param $json
     * @return bool
     */
    private function isIncludes($data, $json)
    {
        if (!is_object($data) && !is_array($data) && $data !== $json) {
            return false;
        }

        if (is_object($data)) {
            if ($this->helper->generateNormalizedJson($data) === $json) {
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
