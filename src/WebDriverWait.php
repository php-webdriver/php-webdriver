<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverWait::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverWait" instead. */
    class WebDriverWait extends \PhpWebDriver\WebDriver\WebDriverWait
    {
    }
}
