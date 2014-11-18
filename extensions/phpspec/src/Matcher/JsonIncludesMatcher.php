<?php

namespace JsonSpec\PhpSpec\Matcher;

use JsonSpec\Helper\FileHelper;
use JsonSpec\PhpSpec\Runner\Maintainer\FileHelperAware;
use PhpSpec\Exception\Example\FailureException;

class JsonIncludesMatcher extends JsonSpecMatcher implements FileHelperAware
{

    /**
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @inheritdoc
     */
    public function setFileHelper(FileHelper $helper)
    {
        $this->fileHelper = $helper;
    }

    /**
     * @return array of names
     */
    protected function getSupportedNames()
    {
        return array('includeJson', 'includeJsonFile');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument, array $options, $matcher = null)
    {
        if ('includeJsonFile' === $matcher) {
            $argument = $this->fileHelper->loadJson($argument);
        }

        return $this->matcher->includes($subject, $argument, $options);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual, array $options)
    {
        return $this->createError('Expected included JSON', $options);
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual, array $options)
    {
        return $this->createError('Expected excluded JSON', $options);
    }

}
