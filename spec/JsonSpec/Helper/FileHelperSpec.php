<?php

namespace spec\JsonSpec\Helper;

use JsonSpec\Exception\MissingDirectoryException;
use JsonSpec\Exception\MissingFileException;
use PhpSpec\ObjectBehavior;

class FileHelperSpec extends ObjectBehavior
{

    private $path;

    public function __construct()
    {
        $this->path = __DIR__ . '/../../support/files';
    }

    public function it_raises_an_error_when_no_directory_is_set()
    {
        $this->shouldThrow(new MissingDirectoryException())->duringLoadJson('one.json');
    }

    public function it_returns_json_when_the_file_exists()
    {
        $this->setDirectory($this->path);
        $this->loadJson('one.json')->shouldReturn('{"value":"from_file"}');
    }

    public function it_ignores_extra_slashes()
    {
        $this->setDirectory($this->path . '/');
        $this->loadJson('one.json')->shouldReturn('{"value":"from_file"}');
    }

    public function it_raises_an_error_when_the_file_does_not_exist()
    {
        $this->setDirectory($this->path);
        $this->shouldThrow(new MissingFileException($this->path . '/bogus.json'))->duringLoadJson('bogus.json');
    }

    public function it_raises_an_error_when_the_directory_does_not_exist()
    {
        $this->setDirectory($this->path . '/bogus');
        $this->shouldThrow(new MissingFileException($this->path . '/bogus/one.json'))->duringLoadJson('one.json');
    }

    public function it_finds_nested_files()
    {
        $this->setDirectory($this->path);
        $this->loadJson('project/one.json')->shouldReturn('{"nested":"inside_folder"}');
        $this->loadJson('project/version/one.json')->shouldReturn('{"nested":"deeply"}');
    }

}
