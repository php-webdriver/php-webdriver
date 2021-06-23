<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchWindowException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchWindowException" instead. */
    class NoSuchWindowException extends \PhpWebDriver\WebDriver\Exception\NoSuchWindowException
    {
    }
}
