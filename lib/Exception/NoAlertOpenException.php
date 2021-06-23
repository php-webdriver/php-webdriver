<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Use PhpWebDriver\WebDriver\Exception\NoSuchAlertException
 */
class NoAlertOpenException extends NoSuchAlertException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoAlertOpenException::class, \Facebook\WebDriver\Exception\NoAlertOpenException::class);
