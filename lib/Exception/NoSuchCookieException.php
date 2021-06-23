<?php

namespace Facebook\WebDriver\Exception;

/**
 * No cookie matching the given path name was found amongst the associated cookies of the current browsing context’s
 * active document.
 */
class NoSuchCookieException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\NoSuchCookieException', 'PhpWebDriver\Exception\NoSuchCookieException');
