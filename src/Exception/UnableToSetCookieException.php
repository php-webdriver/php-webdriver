<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnableToSetCookieException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnableToSetCookieException" instead. */
    class UnableToSetCookieException extends \PhpWebDriver\WebDriver\Exception\UnableToSetCookieException
    {
    }
}
