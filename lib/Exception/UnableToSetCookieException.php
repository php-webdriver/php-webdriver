<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command to set a cookie’s value could not be satisfied.
 */
class UnableToSetCookieException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnableToSetCookieException::class, \Facebook\WebDriver\Exception\UnableToSetCookieException::class);
