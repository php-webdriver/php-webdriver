<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriver::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriver" instead. */
    class WebDriver extends \PhpWebDriver\WebDriver\WebDriver
    {
    }
}
