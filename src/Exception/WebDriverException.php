<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\WebDriverException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\WebDriverException" instead. */
    class WebDriverException extends \PhpWebDriver\WebDriver\Exception\WebDriverException
    {
    }
}
