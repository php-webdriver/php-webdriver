<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Removed in W3C WebDriver, see https://github.com/php-webdriver/php-webdriver/pull/686
 */
class IMENotAvailableException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\IMENotAvailableException::class, \Facebook\WebDriver\Exception\IMENotAvailableException::class);
