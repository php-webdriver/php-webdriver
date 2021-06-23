<?php

namespace Facebook\WebDriver\Exception;

/**
 * A command could not be executed because the remote end is not aware of it.
 */
class UnknownCommandException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\UnknownCommandException', 'PhpWebDriver\Exception\UnknownCommandException');
