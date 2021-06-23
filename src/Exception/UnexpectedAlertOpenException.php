<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnexpectedAlertOpenException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnexpectedAlertOpenException" instead. */
    class UnexpectedAlertOpenException extends \PhpWebDriver\WebDriver\Exception\UnexpectedAlertOpenException
    {
    }
}
