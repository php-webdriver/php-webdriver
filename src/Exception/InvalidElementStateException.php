<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InvalidElementStateException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InvalidElementStateException" instead. */
    class InvalidElementStateException extends \PhpWebDriver\WebDriver\Exception\InvalidElementStateException
    {
    }
}
