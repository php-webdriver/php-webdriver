<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\WebDriverAction;

class WebDriverClickAction extends WebDriverMouseAction implements WebDriverAction
{
    public function perform()
    {
        $this->mouse->click($this->getActionLocation());
    }
}

class_alias('Facebook\WebDriver\Interactions\Internal\WebDriverClickAction', 'PhpWebDriver\Interactions\Internal\WebDriverClickAction');
