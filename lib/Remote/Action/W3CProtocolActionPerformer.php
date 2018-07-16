<?php

namespace Facebook\WebDriver\Remote\Action;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\W3CActionExecuteMethod;
use Facebook\WebDriver\WebDriverAction;

class W3CProtocolActionPerformer implements WebDriverActionPerformer
{
    /**
     * @var W3CActionExecuteMethod
     */
    private $interactionExecutionMethod;
    
    /**
     * W3CProtocolActionPerformer constructor.
     * @param W3CActionExecuteMethod $interactionExecutionMethod
     */
    public function __construct(W3CActionExecuteMethod $interactionExecutionMethod)
    {
        $this->interactionExecutionMethod = $interactionExecutionMethod;
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
        $this->interactionExecutionMethod->executeAll();
    }
}