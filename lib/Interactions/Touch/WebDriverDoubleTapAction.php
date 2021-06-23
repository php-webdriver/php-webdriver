<?php

namespace Facebook\WebDriver\Interactions\Touch;

use Facebook\WebDriver\WebDriverAction;

class WebDriverDoubleTapAction extends WebDriverTouchAction implements WebDriverAction
{
    public function perform()
    {
        $this->touchScreen->doubleTap($this->locationProvider);
    }
}

class_alias('Facebook\WebDriver\Interactions\Touch\WebDriverDoubleTapAction', 'PhpWebDriver\Interactions\Touch\WebDriverDoubleTapAction');
