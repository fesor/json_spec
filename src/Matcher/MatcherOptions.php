<?php

namespace JsonSpec\Matcher;

class MatcherOptions
{

    /**
     * @var array of keys
     */
    protected $excludedKeys;

    /**
     * @var string
     */
    protected $path;


    /**
     * @param array $excludedKeys
     */
    public function __construct(array $excludedKeys = array())
    {
        $this->excludedKeys = $excludedKeys;
    }


    /**
     * @return $this
     */
    public function including()
    {
        $this->excludedKeys = array_diff($this->excludedKeys, func_get_args());

        return $this;
    }

    /**
     * @return $this
     */
    public function excluding()
    {
        $this->excludedKeys = array_merge($this->excludedKeys, func_get_args());

        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedKeys()
    {
        return $this->excludedKeys;
    }

    /**
     * @param string $path
     */
    public function atPath($path)
    {
        $i = 0;
        $i++;
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}
