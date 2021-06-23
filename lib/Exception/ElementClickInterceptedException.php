<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * The Element Click command could not be completed because the element receiving the events is obscuring the element
 * that was requested clicked.
 */
class ElementClickInterceptedException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\ElementClickInterceptedException::class, \Facebook\WebDriver\Exception\ElementClickInterceptedException::class);
