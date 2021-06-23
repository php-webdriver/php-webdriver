<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\WebDriverAction;

class WebDriverDoubleClickAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->doubleClick($this->getActionLocation());
    }
}

class_alias('Facebook\WebDriver\Interactions\Internal\WebDriverDoubleClickAction', 'PhpWebDriver\Interactions\Internal\WebDriverDoubleClickAction');
