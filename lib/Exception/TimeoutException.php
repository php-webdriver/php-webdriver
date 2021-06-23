<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * An operation did not complete before its timeout expired.
 */
class TimeoutException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\TimeoutException::class, \Facebook\WebDriver\Exception\TimeoutException::class);
