<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnknownServerException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnknownServerException" instead. */
    class UnknownServerException extends \PhpWebDriver\WebDriver\Exception\UnknownServerException
    {
    }
}
