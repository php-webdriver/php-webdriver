<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverDispatcher::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverDispatcher" instead. */
    class WebDriverDispatcher extends \PhpWebDriver\WebDriver\WebDriverDispatcher
    {
    }
}
