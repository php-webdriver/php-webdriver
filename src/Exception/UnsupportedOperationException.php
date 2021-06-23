<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnsupportedOperationException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnsupportedOperationException" instead. */
    class UnsupportedOperationException extends \PhpWebDriver\WebDriver\Exception\UnsupportedOperationException
    {
    }
}
