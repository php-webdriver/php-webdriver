<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Removed in W3C WebDriver, see https://github.com/php-webdriver/php-webdriver/pull/686
 */
class NoStringLengthException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoStringLengthException::class, \Facebook\WebDriver\Exception\NoStringLengthException::class);
