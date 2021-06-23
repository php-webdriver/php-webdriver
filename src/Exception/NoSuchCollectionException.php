<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchCollectionException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchCollectionException" instead. */
    class NoSuchCollectionException extends \PhpWebDriver\WebDriver\Exception\NoSuchCollectionException
    {
    }
}
