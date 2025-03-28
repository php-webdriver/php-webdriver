<?php

namespace Facebook\WebDriver\Firefox;

use Facebook\WebDriver\Remote\Service\DriverService;

class FirefoxDriverService extends DriverService
{
    /**
     * @var string Name of the environment variable storing the path to the driver binary
     */
    public const WEBDRIVER_FIREFOX_DRIVER = 'WEBDRIVER_FIREFOX_DRIVER';
    /**
     * @var string Name of the environment variable which is the port for webdriver
     */
    public const WEBDRIVER_FIREFOX_CUSTOM_PORT = 'WEBDRIVER_FIREFOX_CUSTOM_PORT';
    /**
     * @var string Default executable used when no other is provided
     * @internal
     */
    public const DEFAULT_EXECUTABLE = 'geckodriver';
    public const DEFAULT_PORT = 9515;

    /**
     * @return static
     */
    public static function createDefaultService()
    {
        $pathToExecutable = getenv(static::WEBDRIVER_FIREFOX_DRIVER);
        if ($pathToExecutable === false || $pathToExecutable === '') {
            $pathToExecutable = static::DEFAULT_EXECUTABLE;
        }

        $port = intval(getenv(static::WEBDRIVER_FIREFOX_CUSTOM_PORT));
        if (!$port) {
            $port = static::DEFAULT_PORT;
        }
        self::checkPortIsAvail($port);
        $args = ['-p=' . $port];

        return new static($pathToExecutable, $port, $args);
    }
}
