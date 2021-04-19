<?php

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Local\LocalWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\Service\DriverCommandExecutor;
use Facebook\WebDriver\Remote\WebDriverCommand;

class ChromeDriver extends LocalWebDriver
{
    /** @var ChromeDevToolsDriver */
    private $devTools;

    /**
     * @return static
     */
    public static function start(DesiredCapabilities $desired_capabilities = null, ChromeDriverService $service = null)
    {
        if ($desired_capabilities === null) {
            $desired_capabilities = DesiredCapabilities::chrome();
        }
        if ($service === null) {
            $service = ChromeDriverService::createDefaultService();
        }
        $executor = new DriverCommandExecutor($service);
        $driver = new static($executor, null, $desired_capabilities);
        $driver->startSession($desired_capabilities);

        return $driver;
    }

    /**
     * @todo Make the class protected
     * @internal
     */
    public function startSession(DesiredCapabilities $desired_capabilities)
    {
        $command = new WebDriverCommand(
            null,
            DriverCommand::NEW_SESSION,
            [
                'capabilities' => [
                    'firstMatch' => [(object) $desired_capabilities->toW3cCompatibleArray()],
                ],
                'desiredCapabilities' => (object) $desired_capabilities->toArray(),
            ]
        );
        $response = $this->executor->execute($command);
        $value = $response->getValue();

        if (!$this->isW3cCompliant = isset($value['capabilities'])) {
            $this->executor->disableW3cCompliance();
        }

        $this->sessionID = $response->getSessionID();
    }

    /**
     * @return ChromeDevToolsDriver
     */
    public function getDevTools()
    {
        if ($this->devTools === null) {
            $this->devTools = new ChromeDevToolsDriver($this);
        }

        return $this->devTools;
    }
}
