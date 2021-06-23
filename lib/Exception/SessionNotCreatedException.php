<?php

namespace Facebook\WebDriver\Exception;

/**
 * A new session could not be created.
 */
class SessionNotCreatedException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\SessionNotCreatedException', 'PhpWebDriver\Exception\SessionNotCreatedException');
