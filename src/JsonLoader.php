<?php

namespace JsonSpec;

use JsonSpec\Exception\MissingDirectoryException;
use JsonSpec\Exception\MissingFileException;

class JsonLoader
{

    private $directory;

    /**
     * @param string $directory
     */
    public function __construct($directory = null)
    {
        $this->setDirectory($directory);
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        if (!is_null($directory)) {
            $this->directory = rtrim($directory, '/').'/';
        }
    }

    /**
     * @param  string $path
     * @return string
     */
    public function loadJson($path)
    {
        if (!$this->directory) {
            throw new MissingDirectoryException();
        }

        $path = $this->directory . $path;
        if (!is_file($path)) {
            throw new MissingFileException($path);
        }

        return file_get_contents($path);
    }

}
