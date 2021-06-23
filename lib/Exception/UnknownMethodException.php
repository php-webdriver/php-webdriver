<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * The requested command matched a known URL but did not match an method for that URL.
 */
class UnknownMethodException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnknownMethodException::class, \Facebook\WebDriver\Exception\UnknownMethodException::class);
