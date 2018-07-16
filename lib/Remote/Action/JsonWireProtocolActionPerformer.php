<?php

namespace Facebook\WebDriver\Remote\Action;


use Facebook\WebDriver\WebDriverAction;

class JsonWireProtocolActionPerformer implements WebDriverActionPerformer
{
    /**
     * @param array | WebDriverAction[] $actions
     */
    public function perform(array $actions)
    {
        foreach ($actions as $action) {
            $action->perform();
        }
    }
}