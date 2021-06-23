<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchDriverException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchDriverException" instead. */
    class NoSuchDriverException extends \PhpWebDriver\WebDriver\Exception\NoSuchDriverException
    {
    }
}
