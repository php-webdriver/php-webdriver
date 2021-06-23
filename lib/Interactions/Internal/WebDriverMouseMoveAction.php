<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\WebDriverAction;

class WebDriverMouseMoveAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->mouseMove($this->getActionLocation());
    }
}
