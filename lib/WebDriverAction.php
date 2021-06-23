<?php

namespace Facebook\WebDriver;

/**
 * Interface representing a single user-interaction action.
 */
interface WebDriverAction
{
    public function perform();
}

class_alias('Facebook\WebDriver\WebDriverAction', 'PhpWebDriver\WebDriverAction');
