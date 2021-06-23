<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A screen capture was made impossible.
 */
class UnableToCaptureScreenException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnableToCaptureScreenException::class, \Facebook\WebDriver\Exception\UnableToCaptureScreenException::class);
