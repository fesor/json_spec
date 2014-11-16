<?php

namespace JsonSpec\PhpSpec\Matcher;

use \JsonSpec\JsonSpecMatcher as Matcher;
use PhpSpec\Exception\Example\FailureException;

abstract class JsonSpecMatcher
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
     * @param  string $expected
     * @param  string $actual
     * @param  array $options
     * @return mixed
     */
    abstract protected function createPositiveError($expected, $actual, array $options);

    /**
     * @param  string $expected
     * @param  string $actual
     * @param  array $options
     * @return mixed
     */
    abstract protected function createNegativeError($expected, $actual, array $options);

    /**
     * @param  string $subject
     * @param  string $argument
     * @param  array $options
     * @param  string $matcher
     * @return bool
     */
    abstract protected function match($subject, $argument, array $options, $matcher = null);

    /**
     * @inheritdoc
     */
    public function supports($name, $subject, array $arguments)
    {
        return in_array($name, $this->getSupportedNames())
            && (1 === count($arguments)
               || (2 === count($arguments) && is_array($arguments[1]))
            )
        ;
    }

    /**
     * @inheritdoc
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        $options = 2 === count($arguments) ?
            $arguments[1] : [];
        if (!$this->match($subject, $arguments[0], $options, $name)) {
            throw $this->createPositiveError($arguments[0], $subject, $options);
        }
    }

    /**
     * @inheritdoc
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        $options = 2 === count($arguments) ?
            $arguments[1] : [];
        if ($this->match($subject, $arguments[0], $options, $name)) {
            throw $this->createNegativeError($arguments[0], $subject, $options);
        }
    }


    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 50;
    }

    protected function createError($message, array $options)
    {
        $path = array_key_exists(Matcher::OPTION_PATH, $options) ?
            $options[Matcher::OPTION_PATH] : null;

        if ($path !== null) {
            $message .= sprintf(' at path \'%s\'', $path);
        }

        return new FailureException($message);
    }

}
