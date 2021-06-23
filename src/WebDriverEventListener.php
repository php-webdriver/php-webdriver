<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverEventListener::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverEventListener" instead. */
    class WebDriverEventListener extends \PhpWebDriver\WebDriver\WebDriverEventListener
    {
    }
}
