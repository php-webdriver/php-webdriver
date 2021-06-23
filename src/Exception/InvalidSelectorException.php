<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InvalidSelectorException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InvalidSelectorException" instead. */
    class InvalidSelectorException extends \PhpWebDriver\WebDriver\Exception\InvalidSelectorException
    {
    }
}
