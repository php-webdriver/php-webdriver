<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnrecognizedExceptionException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnrecognizedExceptionException" instead. */
    class UnrecognizedExceptionException extends \PhpWebDriver\WebDriver\Exception\UnrecognizedExceptionException
    {
    }
}
