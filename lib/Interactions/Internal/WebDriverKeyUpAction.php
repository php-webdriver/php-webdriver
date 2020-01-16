<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\WebDriverAction;

class WebDriverKeyUpAction extends WebDriverSingleKeyAction implements WebDriverAction
{
    public function perform()
    {
        $this->focusOnElement();
        $this->keyboard->releaseKey($this->key);
    }
}
