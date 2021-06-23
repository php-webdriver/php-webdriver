<?php

namespace PhpWebDriver\WebDriver\Interactions;

use PhpWebDriver\WebDriver\WebDriverAction;

/**
 * An action for aggregating actions and triggering all of them afterwards.
 */
class WebDriverCompositeAction implements WebDriverAction
{
    /**
     * @var WebDriverAction[]
     */
    private $actions = [];

    /**
     * Add an WebDriverAction to the sequence.
     *
     * @param WebDriverAction $action
     * @return WebDriverCompositeAction The current instance.
     */
    public function addAction(WebDriverAction $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Get the number of actions in the sequence.
     *
     * @return int The number of actions.
     */
    public function getNumberOfActions()
    {
        return count($this->actions);
    }

    /**
     * Perform the sequence of actions.
     */
    public function perform()
    {
        foreach ($this->actions as $action) {
            $action->perform();
        }
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\WebDriverCompositeAction::class, \Facebook\WebDriver\Interactions\WebDriverCompositeAction::class);
