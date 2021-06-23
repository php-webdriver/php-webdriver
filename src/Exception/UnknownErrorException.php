<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnknownErrorException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnknownErrorException" instead. */
    class UnknownErrorException extends \PhpWebDriver\WebDriver\Exception\UnknownErrorException
    {
    }
}
