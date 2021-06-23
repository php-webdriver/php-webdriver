<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\WebDriverAction;

/**
 * Move to the location and then release the mouse key.
 */
class WebDriverButtonReleaseAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->mouseUp($this->getActionLocation());
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverButtonReleaseAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverButtonReleaseAction::class);
