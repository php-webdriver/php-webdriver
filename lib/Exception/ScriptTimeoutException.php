<?php

namespace Facebook\WebDriver\Exception;

/**
 * A script did not complete before its timeout expired.
 */
class ScriptTimeoutException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\ScriptTimeoutException', 'PhpWebDriver\Exception\ScriptTimeoutException');
