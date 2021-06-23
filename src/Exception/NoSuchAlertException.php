<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchAlertException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchAlertException" instead. */
    class NoSuchAlertException extends \PhpWebDriver\WebDriver\Exception\NoSuchAlertException
    {
    }
}
