<?php

namespace JsonSpec\PhpSpec\Matcher;

use JsonSpec\Helper\FileHelper;
use JsonSpec\PhpSpec\Runner\Maintainer\FileHelperAware;
use PhpSpec\Exception\Example\FailureException;

class BeJsonEqualMatcher extends JsonSpecMatcher implements FileHelperAware
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
     * @inheritdoc
     */
    protected function getSupportedNames()
    {
        return array('beJsonEqual', 'beJsonEqualFile');
    }

    /**
     * @inheritdoc
     */
    protected function match($subject, $argument, array $options, $matcher = null)
    {
        if ('beJsonEqualFile' === $matcher) {
            $argument = $this->fileHelper->loadJson($argument);
        }

        return $this->matcher->isEqual($subject, $argument, $options);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual, array $options)
    {
        return $this->createError('Expected equivalent JSON', $options);
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual, array $options)
    {
        return $this->createError('Expected inequivalent JSON', $options);
    }

    /**
     * @param string $message
     * @param array $options
     * @return FailureException
     */
    protected function createError($message, $options)
    {
        return new FailureException($message);
    }

}
