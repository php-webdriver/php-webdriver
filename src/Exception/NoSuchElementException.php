<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchElementException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchElementException" instead. */
    class NoSuchElementException extends \PhpWebDriver\WebDriver\Exception\NoSuchElementException
    {
    }
}
