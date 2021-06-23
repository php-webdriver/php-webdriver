<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverAlert::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverAlert" instead. */
    class WebDriverAlert extends \PhpWebDriver\WebDriver\WebDriverAlert
    {
    }
}
