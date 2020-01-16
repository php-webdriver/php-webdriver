<?php

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\Service\DriverService;

class ChromeDriverService extends DriverService
{
    /**
     * The environment variable storing the path to the chrome driver executable.
     * @deprecated Use ChromeDriverService::CHROME_DRIVER_EXECUTABLE
     */
    const CHROME_DRIVER_EXE_PROPERTY = 'webdriver.chrome.driver';
    // The environment variable storing the path to the chrome driver executable
    const CHROME_DRIVER_EXECUTABLE = 'WEBDRIVER_CHROME_DRIVER';

    /**
     * @return static
     */
    public static function createDefaultService()
    {
        $exe = getenv(self::CHROME_DRIVER_EXECUTABLE) ?: getenv(self::CHROME_DRIVER_EXE_PROPERTY);
        $port = 9515; // TODO: Get another port if the default port is used.
        $args = ['--port=' . $port];

        return new static($exe, $port, $args);
    }
}
