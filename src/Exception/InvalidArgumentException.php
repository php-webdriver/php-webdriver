<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InvalidArgumentException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InvalidArgumentException" instead. */
    class InvalidArgumentException extends \PhpWebDriver\WebDriver\Exception\InvalidArgumentException
    {
    }
}
