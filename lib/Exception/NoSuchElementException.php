<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * An element could not be located on the page using the given search parameters.
 */
class NoSuchElementException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoSuchElementException::class, \Facebook\WebDriver\Exception\NoSuchElementException::class);
