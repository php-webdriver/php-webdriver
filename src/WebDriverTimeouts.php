<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverTimeouts::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverTimeouts" instead. */
    class WebDriverTimeouts extends \PhpWebDriver\WebDriver\WebDriverTimeouts
    {
    }
}
