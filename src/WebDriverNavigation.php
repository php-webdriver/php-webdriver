<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverNavigation::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverNavigation" instead. */
    class WebDriverNavigation extends \PhpWebDriver\WebDriver\WebDriverNavigation
    {
    }
}
