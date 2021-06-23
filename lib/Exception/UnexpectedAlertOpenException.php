<?php

namespace Facebook\WebDriver\Exception;

/**
 * A modal dialog was open, blocking this operation.
 */
class UnexpectedAlertOpenException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\UnexpectedAlertOpenException', 'PhpWebDriver\Exception\UnexpectedAlertOpenException');
