<?php

namespace JsonSpec\Matcher;

use JsonSpec\Exception\MissingPathException;
use JsonSpec\Helper\JsonHelper;

class JsonHavePathMatcher
{
    /**
     * @var JsonHelper
     */
    private $helper;

    /**
     * @param JsonHelper $helper
     */
    public function __construct(JsonHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param string $json
     * @param string $path
     * @return bool
     */
    public function match($json, $path)
    {
        try {
            $this->helper->parse($json, $path);
        } catch(MissingPathException $e) {
            return false;
        }

        return true;
    }

}