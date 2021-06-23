<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * No cookie matching the given path name was found amongst the associated cookies of the current browsing context’s
 * active document.
 */
class NoSuchCookieException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoSuchCookieException::class, \Facebook\WebDriver\Exception\NoSuchCookieException::class);
