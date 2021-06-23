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

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverMouseMoveAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverMouseMoveAction::class);
