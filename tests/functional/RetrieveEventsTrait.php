<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebDriver;

trait RetrieveEventsTrait
{
    /** @var RemoteWebDriver $driver */
    public $driver;

    private function retrieveLoggedKeyboardEvents(): array
    {
        return $this->retrieveLoggerEvents(WebDriverBy::id('keyboardEventsLog'));
    }

    private function retrieveLoggedMouseEvents(): array
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
