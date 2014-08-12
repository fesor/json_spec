<?php

namespace JsonSpec\PhpSpec\Matcher;

use \JsonSpec\JsonSpecMatcher as Matcher;

abstract class JsonSpecMatcher implements DelayedMatcherInterface
{

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * @param Matcher $matcher
     */
    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * @return array of names
     */
    abstract protected function getSupportedNames();

    /**
     * @param $expected
     * @param $actual
     * @return mixed
     */
    abstract protected function createPositiveError($expected, $actual);

    /**
     * @param $expected
     * @param $actual
     * @return mixed
     */
    abstract protected function createNegativeError($expected, $actual);


    /**
     * @param $subject
     * @param $argument
     * @return bool
     */
    abstract protected function match($subject, $argument);

    /**
     * @inheritdoc
     */
    public function supports($name, $subject, array $arguments)
    {
        return in_array($name, $this->getSupportedNames())
            && 1 === count($arguments);
    }

    /**
     * @inheritdoc
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        if (!$this->match($subject, $arguments[0])) {
            throw $this->createPositiveError($arguments[0], $subject);
        }
    }

    /**
     * @inheritdoc
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        if ($this->match($subject, $arguments[0])) {
            throw $this->createNegativeError($arguments[0], $subject);
        }
    }

    /**
     * @inheritdoc
     */
    public function promise()
    {
        return $this->matcher->getOptions();
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 50;
    }

    /**
     * @return \JsonSpec\MatcherOptions
     */
    protected function getOptions()
    {
        return $this->matcher->getOptions();
    }

}
