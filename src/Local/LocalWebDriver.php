<?php

namespace Facebook\WebDriver\Local;

class_exists(\PhpWebDriver\WebDriver\Local\LocalWebDriver::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Local\LocalWebDriver" instead. */
    class LocalWebDriver extends \PhpWebDriver\WebDriver\Local\LocalWebDriver
    {
    }
}
