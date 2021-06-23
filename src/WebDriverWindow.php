<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverWindow::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverWindow" instead. */
    class WebDriverWindow extends \PhpWebDriver\WebDriver\WebDriverWindow
    {
    }
}
