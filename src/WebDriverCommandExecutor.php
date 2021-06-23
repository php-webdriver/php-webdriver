<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverCommandExecutor::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverCommandExecutor" instead. */
    class WebDriverCommandExecutor extends \PhpWebDriver\WebDriver\WebDriverCommandExecutor
    {
    }
}
