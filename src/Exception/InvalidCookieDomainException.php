<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InvalidCookieDomainException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InvalidCookieDomainException" instead. */
    class InvalidCookieDomainException extends \PhpWebDriver\WebDriver\Exception\InvalidCookieDomainException
    {
    }
}
