<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * Indicates that a command that should have executed properly cannot be supported for some reason.
 */
class UnsupportedOperationException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnsupportedOperationException::class, \Facebook\WebDriver\Exception\UnsupportedOperationException::class);
