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
