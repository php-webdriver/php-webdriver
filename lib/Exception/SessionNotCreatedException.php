<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A new session could not be created.
 */
class SessionNotCreatedException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\SessionNotCreatedException::class, \Facebook\WebDriver\Exception\SessionNotCreatedException::class);
