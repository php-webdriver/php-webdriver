<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Use PhpWebDriver\WebDriver\Exception\JavascriptErrorException
 */
class UnexpectedJavascriptException extends JavascriptErrorException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnexpectedJavascriptException::class, \Facebook\WebDriver\Exception\UnexpectedJavascriptException::class);
