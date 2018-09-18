<?php

namespace Facebook\WebDriver\Remote\Action;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\BunchActionExecuteMethod;
use Facebook\WebDriver\WebDriverAction;

class W3CProtocolActionPerformer implements WebDriverActionPerformer
{
    /**
     * @var BunchActionExecuteMethod
     */
    private $executionMethod;

    /**
     * W3CProtocolActionPerformer constructor.
     * @param BunchActionExecuteMethod $executionMethod
     */
    public function __construct(BunchActionExecuteMethod $executionMethod)
    {
        $this->executionMethod = $executionMethod;
    }

    /**
     * @param array | WebDriverAction[] $actions
     * @throws WebDriverException
     */
    public function perform(array $actions)
    {
        foreach ($actions as $action) {
            $action->perform();
        }
        $this->executionMethod->executeAll();
    }
}