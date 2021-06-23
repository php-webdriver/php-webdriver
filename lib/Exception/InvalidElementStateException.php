<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command could not be completed because the element is in an invalid state, e.g. attempting to clear an element
 * that isn’t both editable and resettable.
 */
class InvalidElementStateException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\InvalidElementStateException::class, \Facebook\WebDriver\Exception\InvalidElementStateException::class);
