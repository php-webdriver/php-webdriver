<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A modal dialog was open, blocking this operation.
 */
class UnexpectedAlertOpenException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnexpectedAlertOpenException::class, \Facebook\WebDriver\Exception\UnexpectedAlertOpenException::class);
