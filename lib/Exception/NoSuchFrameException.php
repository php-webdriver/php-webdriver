<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command to switch to a frame could not be satisfied because the frame could not be found.
 */
class NoSuchFrameException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoSuchFrameException::class, \Facebook\WebDriver\Exception\NoSuchFrameException::class);
