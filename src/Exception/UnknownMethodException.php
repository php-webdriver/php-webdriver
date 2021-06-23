<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnknownMethodException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnknownMethodException" instead. */
    class UnknownMethodException extends \PhpWebDriver\WebDriver\Exception\UnknownMethodException
    {
    }
}
