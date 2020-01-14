<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\WebDriverAction;

class WebDriverKeyDownAction extends WebDriverSingleKeyAction implements WebDriverAction
{
    public function perform()
    {
        $this->focusOnElement();
        $this->keyboard->pressKey($this->key);
    }
}
