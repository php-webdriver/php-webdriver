<?php

namespace Facebook\WebDriver\Exception;

/**
 * A command to set a cookie’s value could not be satisfied.
 */
class UnableToSetCookieException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\UnableToSetCookieException', 'PhpWebDriver\Exception\UnableToSetCookieException');
