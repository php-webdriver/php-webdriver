<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * An attempt was made to operate on a modal dialog when one was not open.
 */
class NoSuchAlertException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoSuchAlertException::class, \Facebook\WebDriver\Exception\NoSuchAlertException::class);
