<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverExpectedCondition::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverExpectedCondition" instead. */
    class WebDriverExpectedCondition extends \PhpWebDriver\WebDriver\WebDriverExpectedCondition
    {
    }
}
