<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoCollectionException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoCollectionException" instead. */
    class NoCollectionException extends \PhpWebDriver\WebDriver\Exception\NoCollectionException
    {
    }
}
