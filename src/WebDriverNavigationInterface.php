<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverNavigationInterface::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverNavigationInterface" instead. */
    class WebDriverNavigationInterface extends \PhpWebDriver\WebDriver\WebDriverNavigationInterface
    {
    }
}
