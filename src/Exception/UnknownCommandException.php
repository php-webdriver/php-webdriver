<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnknownCommandException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnknownCommandException" instead. */
    class UnknownCommandException extends \PhpWebDriver\WebDriver\Exception\UnknownCommandException
    {
    }
}
