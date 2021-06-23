<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * The arguments passed to a command are either invalid or malformed.
 */
class InvalidArgumentException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\InvalidArgumentException::class, \Facebook\WebDriver\Exception\InvalidArgumentException::class);
