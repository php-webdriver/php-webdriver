<?php

namespace Facebook\WebDriver\Exception;

/**
 * The arguments passed to a command are either invalid or malformed.
 */
class InvalidArgumentException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\InvalidArgumentException', 'PhpWebDriver\Exception\InvalidArgumentException');
