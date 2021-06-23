<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command could not be executed because the remote end is not aware of it.
 */
class UnknownCommandException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnknownCommandException::class, \Facebook\WebDriver\Exception\UnknownCommandException::class);
