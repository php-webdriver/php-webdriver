<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\WebDriverAction;

/**
 * You can call it 'Right Click' if you like.
 */
class WebDriverContextClickAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->contextClick($this->getActionLocation());
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverContextClickAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverContextClickAction::class);
