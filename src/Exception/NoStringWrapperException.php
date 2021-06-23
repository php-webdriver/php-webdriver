<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoStringWrapperException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoStringWrapperException" instead. */
    class NoStringWrapperException extends \PhpWebDriver\WebDriver\Exception\NoStringWrapperException
    {
    }
}
