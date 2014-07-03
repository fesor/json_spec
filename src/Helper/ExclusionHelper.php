<?php

namespace JsonSpec\Helper;

class ExclusionHelper
{

    private $excludedKeys;

    public function __construct(array $excludedKeys = array('id'))
    {
        $this->excludedKeys = $excludedKeys;
    }

    /**
     * Recursively removes specific keys from
     *
     * @param $data
     * @return mixed
     */
    public function excludeKeys($data)
    {
        if (is_object($data)) {
            $object = new \stdClass();
            foreach(get_object_vars($data) as $key => $value) {
                if (in_array($key, $this->excludedKeys)) continue;
                $object->$key = $this->excludeKeys($value);
            }

            return $object;
        }

        if (is_array($data)) {

            return array_map(function ($data) {
                return $this->excludeKeys($data);
            }, $data);
        }

        return $data;
    }
}
