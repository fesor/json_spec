<?php

namespace JsonSpec\Helper;

class ExclusionHelper
{

    private $excludedKeys;

    public function __construct(array $excludedKeys = array())
    {
        $this->excludedKeys = $excludedKeys;
    }

    public function getExcludedKeys()
    {
        return $this->excludedKeys;
    }

    /**
     * Recursively removes specific keys from
     *
     * @param $data
     * @param array|null excludedKeys
     * @return mixed
     */
    public function excludeKeys($data, array $excludedKeys = null)
    {
        if (!is_array($excludedKeys)) {
            $excludedKeys = $this->excludedKeys;
        }

        if (is_object($data)) {
            $object = new \stdClass();
            foreach(get_object_vars($data) as $key => $value) {
                if (in_array($key, $excludedKeys)) continue;
                $object->$key = $this->excludeKeys($value, $excludedKeys);
            }

            return $object;
        }

        if (is_array($data)) {

            return array_map(function ($data) use ($excludedKeys) {
                return $this->excludeKeys($data, $excludedKeys);
            }, $data);
        }

        return $data;
    }
}
