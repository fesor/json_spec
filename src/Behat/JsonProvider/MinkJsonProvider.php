<?php

namespace JsonSpec\Behat\JsonProvider;

use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Behat\Behat\EventDispatcher\Event\StepTested;
use Behat\Behat\Tester\Result\ExecutedStepResult;
use Behat\Mink\Mink;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MinkJsonProvider implements EventSubscriberInterface
{

    /**
     * @var JsonHolder
     */
    private $jsonHolder;

    /**
     * @var Mink
     */
    private $mink;

    /**
     * @param JsonHolder $jsonHolder
     * @param Mink $mink
     */
    public function __construct(JsonHolder $jsonHolder, Mink $mink)
    {
        $this->jsonHolder = $jsonHolder;
        $this->mink = $mink;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            StepTested::AFTER => 'provideJsonResponse'
        );
    }


    public function provideJsonResponse(AfterStepTested $event)
    {
        $testResult = $event->getTestResult();

        if (!$testResult instanceof ExecutedStepResult) {
            return;
        }

        $callResult = $testResult->getCallResult()->getReturn();
        if (!$callResult instanceof \Behat\Mink\Element\DocumentElement) {
            return;
        }

        $response = $callResult->getContent();
        $this->jsonHolder->setJson($response);
    }

}
