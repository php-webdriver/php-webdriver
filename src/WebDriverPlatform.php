<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverPlatform::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverPlatform" instead. */
    class WebDriverPlatform extends \PhpWebDriver\WebDriver\WebDriverPlatform
    {
    }
}
