<?php

namespace Facebook\WebDriver\Exception;

/**
 * Indicates that a command that should have executed properly cannot be supported for some reason.
 */
class UnsupportedOperationException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\UnsupportedOperationException', 'PhpWebDriver\Exception\UnsupportedOperationException');
