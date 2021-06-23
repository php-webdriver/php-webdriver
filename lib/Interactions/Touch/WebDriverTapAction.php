<?php

namespace PhpWebDriver\WebDriver\Interactions\Touch;

use PhpWebDriver\WebDriver\WebDriverAction;

class WebDriverTapAction extends WebDriverTouchAction implements WebDriverAction
{
    public function perform()
    {
        $this->touchScreen->tap($this->locationProvider);
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Touch\WebDriverTapAction::class, \Facebook\WebDriver\Interactions\Touch\WebDriverTapAction::class);
