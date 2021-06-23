<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverOptions::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverOptions" instead. */
    class WebDriverOptions extends \PhpWebDriver\WebDriver\WebDriverOptions
    {
    }
}
