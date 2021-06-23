<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InvalidSessionIdException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InvalidSessionIdException" instead. */
    class InvalidSessionIdException extends \PhpWebDriver\WebDriver\Exception\InvalidSessionIdException
    {
    }
}
