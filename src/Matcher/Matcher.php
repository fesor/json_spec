<?php

namespace JsonSpec\Matcher;

use JsonSpec\Helper\JsonHelper;

abstract class Matcher
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
    public function __construct(JsonHelper $helper, MatcherOptions $options = null)
    {
        $this->options = $options ?
            $options : new MatcherOptions();
        $this->helper = $helper;
    }

    /**
     * @param  string $json
     * @param  string $expected
     * @return bool
     */
    abstract public function match($json, $expected);

    /**
     * @param MatcherOptions $options
     */
    public function setOptions(MatcherOptions $options)
    {
        $this->options = $options;
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
