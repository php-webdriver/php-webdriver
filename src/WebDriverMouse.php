<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverMouse::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverMouse" instead. */
    class WebDriverMouse extends \PhpWebDriver\WebDriver\WebDriverMouse
    {
    }
}
