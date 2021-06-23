<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Remote\WebDriverCommand;
use PhpWebDriver\WebDriver\Remote\WebDriverResponse;

/**
 * Interface for all command executor.
 */
interface WebDriverCommandExecutor
{
    /**
     * @param WebDriverCommand $command
     *
     * @return WebDriverResponse
     */
    public function execute(WebDriverCommand $command);
}

class_alias(\PhpWebDriver\WebDriver\WebDriverCommandExecutor::class, \Facebook\WebDriver\WebDriverCommandExecutor::class);
