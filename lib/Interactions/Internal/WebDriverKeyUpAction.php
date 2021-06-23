<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

class WebDriverKeyUpAction extends WebDriverSingleKeyAction
{
    public function perform()
    {
        $this->focusOnElement();
        $this->keyboard->releaseKey($this->key);
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverKeyUpAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverKeyUpAction::class);
