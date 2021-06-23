<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoStringLengthException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoStringLengthException" instead. */
    class NoStringLengthException extends \PhpWebDriver\WebDriver\Exception\NoStringLengthException
    {
    }
}
