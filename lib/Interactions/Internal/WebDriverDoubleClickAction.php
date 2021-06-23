<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\WebDriverAction;

class WebDriverDoubleClickAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->doubleClick($this->getActionLocation());
    }
}
