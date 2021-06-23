<?php

namespace Facebook\WebDriver\Exception;

/**
 * An unknown error occurred in the remote end while processing the command.
 */
class UnknownErrorException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\UnknownErrorException', 'PhpWebDriver\Exception\UnknownErrorException');
