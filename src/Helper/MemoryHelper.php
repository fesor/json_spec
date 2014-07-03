<?php

namespace JsonSpec\Helper;

class MemoryHelper
{

    protected $memory;

    public function __construct()
    {
        $this->forget();
    }

    public function getRemembered()
    {
        return $this->memory;
    }

    public function memorize($key, $value)
    {
        $this->memory[$key] = $value;
    }

    public function remember($value)
    {
        return preg_replace_callback('/\{\$([^\}]+?)}/', function ($matched) {
            return isset($this->memory[$matched[1]]) ?
                $this->memory[$matched[1]] : $matched[0];
        }, $value);
    }

    public function forget()
    {
        $this->memory = array();
    }
}
