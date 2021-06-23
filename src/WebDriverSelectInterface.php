<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverSelectInterface::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverSelectInterface" instead. */
    class WebDriverSelectInterface extends \PhpWebDriver\WebDriver\WebDriverSelectInterface
    {
    }
}
