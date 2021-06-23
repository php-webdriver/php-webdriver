<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverCapabilities::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverCapabilities" instead. */
    class WebDriverCapabilities extends \PhpWebDriver\WebDriver\WebDriverCapabilities
    {
    }
}
