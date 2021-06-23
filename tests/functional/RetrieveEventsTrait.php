<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Remote\RemoteWebDriver;

trait RetrieveEventsTrait
{
    /** @var RemoteWebDriver $driver */
    public $driver;

    /**
     * @return array
     */
    private function retrieveLoggedKeyboardEvents()
    {
        return $this->retrieveLoggerEvents(WebDriverBy::id('keyboardEventsLog'));
    }

    /**
     * @return array
     */
    private function retrieveLoggedMouseEvents()
    {
        return $this->retrieveLoggerEvents(WebDriverBy::id('mouseEventsLog'));
    }

    /**
     * @return false|string[]
     */
    private function retrieveLoggerEvents(WebDriverBy $by)
    {
        $logElement = $this->driver->findElement($by);

        $text = trim($logElement->getText());

        return array_map('trim', explode("\n", $text));
    }
}
