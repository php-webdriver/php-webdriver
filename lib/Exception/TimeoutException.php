<?php

namespace Facebook\WebDriver\Exception;

/**
 * An operation did not complete before its timeout expired.
 */
class TimeoutException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\TimeoutException', 'PhpWebDriver\Exception\TimeoutException');
