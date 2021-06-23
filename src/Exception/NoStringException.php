<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoStringException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoStringException" instead. */
    class NoStringException extends \PhpWebDriver\WebDriver\Exception\NoStringException
    {
    }
}
