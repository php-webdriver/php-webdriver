<?php

namespace Facebook\WebDriver\Exception;

/**
 * An error occurred while executing JavaScript supplied by the user.
 */
class JavascriptErrorException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\JavascriptErrorException', 'PhpWebDriver\Exception\JavascriptErrorException');
