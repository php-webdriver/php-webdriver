<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\IndexOutOfBoundsException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\IndexOutOfBoundsException" instead. */
    class IndexOutOfBoundsException extends \PhpWebDriver\WebDriver\Exception\IndexOutOfBoundsException
    {
    }
}
