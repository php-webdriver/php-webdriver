<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * Occurs if the given session id is not in the list of active sessions, meaning the session either does not exist
 * or that it’s not active.
 */
class InvalidSessionIdException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\InvalidSessionIdException::class, \Facebook\WebDriver\Exception\InvalidSessionIdException::class);
