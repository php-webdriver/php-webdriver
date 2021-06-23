<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverTargetLocator::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverTargetLocator" instead. */
    class WebDriverTargetLocator extends \PhpWebDriver\WebDriver\WebDriverTargetLocator
    {
    }
}
