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
