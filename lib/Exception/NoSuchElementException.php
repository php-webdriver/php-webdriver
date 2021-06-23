<?php

namespace Facebook\WebDriver\Exception;

/**
 * An element could not be located on the page using the given search parameters.
 */
class NoSuchElementException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\NoSuchElementException', 'PhpWebDriver\Exception\NoSuchElementException');
