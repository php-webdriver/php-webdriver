<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchDocumentException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchDocumentException" instead. */
    class NoSuchDocumentException extends \PhpWebDriver\WebDriver\Exception\NoSuchDocumentException
    {
    }
}
