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
    protected function match($subject, $argument, $matcher = null)
    {
        if ('beJsonEqualFile' === $matcher) {
            $argument = $this->fileHelper->loadJson($argument);
        }

        return $this->matcher->isEqual($subject, $argument);
    }

    /**
     * @inheritdoc
     */
    protected function createPositiveError($expected, $actual)
    {
        return $this->createError('Expected equivalent JSON');
    }

    /**
     * @inheritdoc
     */
    protected function createNegativeError($expected, $actual)
    {
        return $this->createError('Expected inequivalent JSON');
    }

    /**
     * @param $message
     * @return FailureException
     */
    private function createError($message)
    {
        return new FailureException($message);
    }

}
