<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\ScriptTimeoutException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\ScriptTimeoutException" instead. */
    class ScriptTimeoutException extends \PhpWebDriver\WebDriver\Exception\ScriptTimeoutException
    {
    }
}
