<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchCookieException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchCookieException" instead. */
    class NoSuchCookieException extends \PhpWebDriver\WebDriver\Exception\NoSuchCookieException
    {
    }
}
