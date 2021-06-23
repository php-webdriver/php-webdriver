<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

class WebDriverKeyDownAction extends WebDriverSingleKeyAction
{
    public function perform()
    {
        $this->focusOnElement();
        $this->keyboard->pressKey($this->key);
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverKeyDownAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverKeyDownAction::class);
