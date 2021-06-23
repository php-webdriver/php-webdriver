<?php

namespace PhpWebDriver\WebDriver\Interactions\Touch;

use PhpWebDriver\WebDriver\WebDriverAction;

class WebDriverLongPressAction extends WebDriverTouchAction implements WebDriverAction
{
    public function perform()
    {
        $this->touchScreen->longPress($this->locationProvider);
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Touch\WebDriverLongPressAction::class, \Facebook\WebDriver\Interactions\Touch\WebDriverLongPressAction::class);
