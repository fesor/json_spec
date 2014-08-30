<?php

namespace JsonSpec\Behat\Definition;

use Behat\Behat\Definition\Annotation\Definition;
use Behat\Behat\Definition\DefinitionDispatcher as BaseDispatcher;
use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Exception\AmbiguousException;
use Behat\Behat\Exception\UndefinedException;
use Behat\Gherkin\Node\StepNode;
use JsonSpec\Behat\Context\JsonSpecContext;

class DefinitionDispatcher extends BaseDispatcher
{

    /**
     * Finds step definition, that match specified step.
     *
     * @param ContextInterface $context
     * @param StepNode         $step
     * @param bool             $skip
     *
     * @return Definition
     *
     * @uses loadDefinitions()
     *
     * @throws AmbiguousException if step description is ambiguous
     * @throws UndefinedException if step definition not found
     */
    public function findDefinition(ContextInterface $context, StepNode $step, $skip = false)
    {
        $text       = $step->getText();
        $multiline  = $step->getArguments();
        $matches    = array();

        // find step to match
        foreach ($this->getDefinitions() as $origRegex => $definition) {
            $transRegex = $this->translateDefinitionRegex($origRegex, $step->getLanguage());

            // if not regex really (string) - transform into it
            if (0 !== strpos($origRegex, '/')) {
                $origRegex  = '/^'.preg_quote($origRegex, '/').'$/';
                $transRegex = '/^'.preg_quote($transRegex, '/').'$/';
            }

            if (preg_match($origRegex, $text, $arguments)
                || ($origRegex !== $transRegex && preg_match($transRegex, $text, $arguments))) {
                // prepare callback arguments
                $arguments = $this->prepareCallbackArgumentsFixed(
                    $context, $definition->getCallbackReflection(), array_slice($arguments, 1), $multiline
                );

                if (!$skip) {
                    // transform arguments
                    foreach ($arguments as &$argument) {
                        foreach ($this->getTransformations() as $trans) {
                            $transRegex = $this->translateDefinitionRegex(
                                $trans->getRegex(), $step->getLanguage()
                            );

                            $newArgument = $trans->transform($transRegex, $context, $argument);
                            if (null !== $newArgument) {
                                $argument = $newArgument;
                            }
                        }
                    }
                }

                // set matched definition
                $definition->setMatchedText($text);
                $definition->setValues($arguments);
                $matches[] = $definition;
            }
        }

        if (count($matches) > 1) {
            throw new AmbiguousException($text, $matches);
        }

        if (0 === count($matches)) {
            throw new UndefinedException($text);
        }

        return $matches[0];
    }

    /**
     * Note: there is a bug in behat < 3.13
     * This is simple fix for it
     *
     * Merges found arguments with multiliners and maps them to the function callback signature.
     *
     * @param ContextInterface            $context   context instance
     * @param \ReflectionFunctionAbstract $refl      callback reflection
     * @param array                       $arguments found arguments
     * @param array                       $multiline multiline arguments of the step
     *
     * @return array
     */
    private function prepareCallbackArguments(ContextInterface $context, \ReflectionFunctionAbstract $refl,
                                              array $arguments, array $multiline)
    {
        $parametersRefl = $refl->getParameters();

        if ($refl->isClosure()) {
            array_shift($parametersRefl);
        }

        $resulting = array();
        foreach ($parametersRefl as $num => $parameterRefl) {
            if (isset($arguments[$parameterRefl->getName()])) {
                $resulting[] = $arguments[$parameterRefl->getName()];
            } elseif (isset($arguments[$num])) {
                $resulting[] = $arguments[$num];
            }
        }

        foreach ($multiline as $argument) {
            $resulting[] = $argument;
        }

        return $resulting;
    }

    private function prepareCallbackArgumentsFixed(ContextInterface $context, \ReflectionFunctionAbstract $refl,
                                                   array $arguments, array $multiline)
    {
        if ($refl->class !== 'JsonSpec\\Behat\\Context\\JsonSpecContext') {
            return $this->prepareCallbackArguments($context, $refl, $arguments, $multiline);
        }

        $parametersRefl = $refl->getParameters();

        if ($refl->isClosure()) {
            array_shift($parametersRefl);
        }
        if (!empty($multiline)) {
            array_pop($parametersRefl);
        }

        $resulting = array();
        foreach ($parametersRefl as $num => $parameterRefl) {
            if (array_key_exists($parameterRefl->getName(), $arguments)) {
                $resulting[] = $arguments[$parameterRefl->getName()];
            } elseif (isset($arguments[$num])) {
                $resulting[] = $arguments[$num];
            }
        }

        foreach ($multiline as $argument) {
            $resulting[] = $argument;
        }

        return $resulting;
    }

}
