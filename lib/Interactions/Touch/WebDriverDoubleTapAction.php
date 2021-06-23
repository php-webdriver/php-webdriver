<?php

namespace PhpWebDriver\WebDriver\Interactions\Touch;

use PhpWebDriver\WebDriver\WebDriverAction;

class WebDriverDoubleTapAction extends WebDriverTouchAction implements WebDriverAction
{
    public function perform()
    {
        $this->touchScreen->doubleTap($this->locationProvider);
    }
}
