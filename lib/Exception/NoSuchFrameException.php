<?php

namespace Facebook\WebDriver\Exception;

/**
 * A command to switch to a frame could not be satisfied because the frame could not be found.
 */
class NoSuchFrameException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\NoSuchFrameException', 'PhpWebDriver\Exception\NoSuchFrameException');
