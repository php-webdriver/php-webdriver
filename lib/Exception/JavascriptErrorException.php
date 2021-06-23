<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * An error occurred while executing JavaScript supplied by the user.
 */
class JavascriptErrorException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\JavascriptErrorException::class, \Facebook\WebDriver\Exception\JavascriptErrorException::class);
