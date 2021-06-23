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
