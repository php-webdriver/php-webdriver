<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * Argument was an invalid selector.
 */
class InvalidSelectorException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\InvalidSelectorException::class, \Facebook\WebDriver\Exception\InvalidSelectorException::class);
