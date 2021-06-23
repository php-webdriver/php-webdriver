<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * An illegal attempt was made to set a cookie under a different domain than the current page.
 */
class InvalidCookieDomainException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\InvalidCookieDomainException::class, \Facebook\WebDriver\Exception\InvalidCookieDomainException::class);
