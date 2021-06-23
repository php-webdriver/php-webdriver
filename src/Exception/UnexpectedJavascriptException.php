<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnexpectedJavascriptException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnexpectedJavascriptException" instead. */
    class UnexpectedJavascriptException extends \PhpWebDriver\WebDriver\Exception\UnexpectedJavascriptException
    {
    }
}
