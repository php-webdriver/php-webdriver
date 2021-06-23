<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A script did not complete before its timeout expired.
 */
class ScriptTimeoutException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\ScriptTimeoutException::class, \Facebook\WebDriver\Exception\ScriptTimeoutException::class);
