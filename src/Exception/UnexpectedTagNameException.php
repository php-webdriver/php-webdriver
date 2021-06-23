<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\UnexpectedTagNameException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\UnexpectedTagNameException" instead. */
    class UnexpectedTagNameException extends \PhpWebDriver\WebDriver\Exception\UnexpectedTagNameException
    {
    }
}
