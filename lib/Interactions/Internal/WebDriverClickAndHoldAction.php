<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\WebDriverAction;

/**
 * Move the the location, click and hold.
 */
class WebDriverClickAndHoldAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->mouseDown($this->getActionLocation());
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverClickAndHoldAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverClickAndHoldAction::class);
