<?php

namespace Facebook\WebDriver\Exception;

/**
 * The requested command matched a known URL but did not match an method for that URL.
 */
class UnknownMethodException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\UnknownMethodException', 'PhpWebDriver\Exception\UnknownMethodException');
