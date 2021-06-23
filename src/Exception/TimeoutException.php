<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\TimeoutException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\TimeoutException" instead. */
    class TimeoutException extends \PhpWebDriver\WebDriver\Exception\TimeoutException
    {
    }
}
