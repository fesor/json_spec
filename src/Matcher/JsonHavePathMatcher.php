<?php

namespace JsonSpec\Matcher;

use JsonSpec\Exception\MissingPathException;
use JsonSpec\Helper\JsonHelper;

class JsonHavePathMatcher extends Matcher
{

    /**
     * @param  string $json
     * @param  string $path
     * @return bool
     */
    public function match($json, $path)
    {
        // get base path
        $basePath = $this->getOptions()->getPath();
        $path = ltrim($basePath . '/' . $path, '/');

        try {
            $this->helper->parse($json, $path);
        } catch (MissingPathException $e) {
            return false;
        }

        return true;
    }

}
