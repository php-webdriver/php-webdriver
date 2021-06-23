<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\WebDriverCurlException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\WebDriverCurlException" instead. */
    class WebDriverCurlException extends \PhpWebDriver\WebDriver\Exception\WebDriverCurlException
    {
    }
}
