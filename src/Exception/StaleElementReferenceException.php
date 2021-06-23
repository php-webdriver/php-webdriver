<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\StaleElementReferenceException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\StaleElementReferenceException" instead. */
    class StaleElementReferenceException extends \PhpWebDriver\WebDriver\Exception\StaleElementReferenceException
    {
    }
}
