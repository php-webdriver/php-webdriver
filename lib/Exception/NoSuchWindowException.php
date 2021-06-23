<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command to switch to a window could not be satisfied because the window could not be found.
 */
class NoSuchWindowException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoSuchWindowException::class, \Facebook\WebDriver\Exception\NoSuchWindowException::class);
