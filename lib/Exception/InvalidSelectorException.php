<?php

namespace Facebook\WebDriver\Exception;

/**
 * Argument was an invalid selector.
 */
class InvalidSelectorException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\InvalidSelectorException', 'PhpWebDriver\Exception\InvalidSelectorException');
