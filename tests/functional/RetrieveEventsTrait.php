<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebDriver;

trait RetrieveEventsTrait
{
    /** @var RemoteWebDriver $driver */
    public $driver;

    /**
     * @return array
     */
    private function retrieveLoggedKeyboardEvents()
    {
        $logElement = $this->driver->findElement(WebDriverBy::id('keyboardEventsLog'));

        return explode("\n", $logElement->getText());
    }

    /**
     * @return array
     */
    private function retrieveLoggedMouseEvents()
    {
        $logElement = $this->driver->findElement(WebDriverBy::id('mouseEventsLog'));

        return explode("\n", $logElement->getText());
    }
}
