<?php

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\Service\DriverService;

class ChromeDriverService extends DriverService
{
    /**
     * The environment variable storing the path to the chrome driver executable.
     * @deprecated Use ChromeDriverService::CHROME_DRIVER_EXECUTABLE
     */
    public const CHROME_DRIVER_EXE_PROPERTY = 'webdriver.chrome.driver';
    /** @var string The environment variable storing the path to the chrome driver executable */
    public const CHROME_DRIVER_EXECUTABLE = 'WEBDRIVER_CHROME_DRIVER';
    /**
     * @var string Name of the environment variable which is the port for webdriver
     */
    public const CHROME_DRIVER_CUSTOM_PORT = 'WEBDRIVER_CHROME_CUSTOM_PORT';
    /**
     * @var string Default executable used when no other is provided
     * @internal
     */
    public const DEFAULT_EXECUTABLE = 'chromedriver';
    public const DEFAULT_PORT = 9515;

    /**
     * @return static
     */
    public static function createDefaultService()
    {
        $pathToExecutable = getenv(self::CHROME_DRIVER_EXECUTABLE) ?: getenv(self::CHROME_DRIVER_EXE_PROPERTY);
        if ($pathToExecutable === false || $pathToExecutable === '') {
            $pathToExecutable = static::DEFAULT_EXECUTABLE;
        }

        $port = intval(getenv(static::CHROME_DRIVER_CUSTOM_PORT));
        if (!$port) {
            $port = static::DEFAULT_PORT;
        }
        $args = ['--port=' . $port];

        return new static($pathToExecutable, $port, $args);
    }
}
