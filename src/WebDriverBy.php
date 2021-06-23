<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverBy::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverBy" instead. */
    class WebDriverBy extends \PhpWebDriver\WebDriver\WebDriverBy
    {
    }
}
