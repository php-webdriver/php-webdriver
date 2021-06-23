<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command failed because the referenced element is no longer attached to the DOM.
 */
class StaleElementReferenceException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\StaleElementReferenceException::class, \Facebook\WebDriver\Exception\StaleElementReferenceException::class);
