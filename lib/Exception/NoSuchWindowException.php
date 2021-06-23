<?php

namespace Facebook\WebDriver\Exception;

/**
 * A command to switch to a window could not be satisfied because the window could not be found.
 */
class NoSuchWindowException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\NoSuchWindowException', 'PhpWebDriver\Exception\NoSuchWindowException');
