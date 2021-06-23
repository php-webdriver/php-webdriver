<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * An unknown error occurred in the remote end while processing the command.
 */
class UnknownErrorException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnknownErrorException::class, \Facebook\WebDriver\Exception\UnknownErrorException::class);
