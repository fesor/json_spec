<?php

namespace JsonSpec\PhpSpec\Matcher;

abstract class JsonSpecMatcher implements DelayedMatcherInterface
{

    /**
     * @var \JsonSpec\Matcher\Matcher
     */
    private $matcher;

    /**
     * @param \JsonSpec\Matcher\Matcher $matcher
     */
    public function __construct(\JsonSpec\Matcher\Matcher $matcher)
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
        if (!$this->matcher->match($subject, $arguments[0])) {
            throw $this->createPositiveError($arguments[0], $subject);
        }
    }

    /**
     * @inheritdoc
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        if ($this->matcher->match($subject, $arguments[0])) {
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
     * @return \JsonSpec\Matcher\MatcherOptions
     */
    protected function getOptions()
    {
        return $this->matcher->getOptions();
    }

}
