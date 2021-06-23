<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\JavascriptErrorException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\JavascriptErrorException" instead. */
    class JavascriptErrorException extends \PhpWebDriver\WebDriver\Exception\JavascriptErrorException
    {
    }
}
