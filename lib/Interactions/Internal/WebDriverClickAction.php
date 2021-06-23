<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\WebDriverAction;

class WebDriverClickAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->click($this->getActionLocation());
    }
}
