<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverAction;
use PhpWebDriver\WebDriver\WebDriverKeyboard;
use PhpWebDriver\WebDriver\WebDriverMouse;

class WebDriverSendKeysAction extends WebDriverKeysRelatedAction implements WebDriverAction
{
    /**
     * @var string
     */
    private $keys = '';

    /**
     * @param WebDriverKeyboard $keyboard
     * @param WebDriverMouse $mouse
     * @param WebDriverLocatable $location_provider
     * @param string $keys
     */
    public function __construct(
        WebDriverKeyboard $keyboard,
        WebDriverMouse $mouse,
        WebDriverLocatable $location_provider = null,
        $keys = ''
    ) {
        parent::__construct($keyboard, $mouse, $location_provider);
        $this->keys = $keys;
    }

    public function perform()
    {
        $this->focusOnElement();
        $this->keyboard->sendKeys($this->keys);
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverSendKeysAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverSendKeysAction::class);
