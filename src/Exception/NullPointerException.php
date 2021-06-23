<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NullPointerException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NullPointerException" instead. */
    class NullPointerException extends \PhpWebDriver\WebDriver\Exception\NullPointerException
    {
    }
}
