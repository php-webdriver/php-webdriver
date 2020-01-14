<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverAction;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverMouse;

abstract class WebDriverSingleKeyAction extends WebDriverKeysRelatedAction implements WebDriverAction
{
    /** @var string */
    protected $key = '';

    public function __construct(
        WebDriverKeyboard $keyboard,
        WebDriverMouse $mouse,
        WebDriverLocatable $location_provider = null,
        $key = ''
    ) {
        parent::__construct($keyboard, $mouse, $location_provider);
        $this->key = $key;
    }
}
