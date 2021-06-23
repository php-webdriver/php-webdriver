<?php

namespace PhpWebDriver\WebDriver;

/**
 * Interface representing a single user-interaction action.
 */
interface WebDriverAction
{
    public function perform();
}

class_alias(\PhpWebDriver\WebDriver\WebDriverAction::class, \Facebook\WebDriver\WebDriverAction::class);
