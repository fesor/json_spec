<?php

namespace JsonSpec\Matcher;

use JsonSpec\Helper\JsonHelper;

class Matcher
{

    /**
     * @var JsonHelper
     */
    protected $helper;

    /**
     * @var MatcherOptions
     */
    protected $options;

    /**
     * @param JsonHelper     $helper
     * @param MatcherOptions $options
     */
    public function __construct(JsonHelper $helper, MatcherOptions $options)
    {
        $this->options = $options;
        $this->helper = $helper;
    }

    /**
     * @return MatcherOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $json
     * @param  null   $path
     * @return string
     */
    protected function scrub($json, $path = null)
    {
        return $this->helper->generateNormalizedJson(
            $this->helper->excludeKeys(
                $this->helper->parse($json, $path),
                $this->options->getExcludedKeys()
            )
        );
    }

}
