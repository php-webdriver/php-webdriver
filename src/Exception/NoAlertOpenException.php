<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoAlertOpenException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoAlertOpenException" instead. */
    class NoAlertOpenException extends \PhpWebDriver\WebDriver\Exception\NoAlertOpenException
    {
    }
}
