<?php

namespace JsonSpec;

use JsonSpec\Exception\NotIncludedException;
use JsonSpec\Helper\JsonHelper;

class JsonIncludesMatcher
{
    /**
     * @var JsonHelper
     */
    private $jsonHelper;


    public function __construct(JsonHelper $jsonHelper)
    {
        $this->jsonHelper = $jsonHelper;
    }

    public function isIncludes($json, $expected, $path = null)
    {
        $expected = $this->jsonHelper->normalize($expected);
        $data = (array) $this->jsonHelper->parse($json, $path);
        foreach ($data as $value) {
            if ($this->jsonHelper->generateNormalizedJson($value) === $expected) {
                return true;
            }
        }

        throw new NotIncludedException($expected, $path);
    }
}
