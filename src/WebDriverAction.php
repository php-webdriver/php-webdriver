<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverAction::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverAction" instead. */
    class WebDriverAction extends \PhpWebDriver\WebDriver\WebDriverAction
    {
    }
}
