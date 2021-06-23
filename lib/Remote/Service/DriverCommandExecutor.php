<?php

namespace PhpWebDriver\WebDriver\Remote\Service;

use PhpWebDriver\WebDriver\Exception\DriverServerDiedException;
use PhpWebDriver\WebDriver\Exception\WebDriverException;
use PhpWebDriver\WebDriver\Remote\DriverCommand;
use PhpWebDriver\WebDriver\Remote\HttpCommandExecutor;
use PhpWebDriver\WebDriver\Remote\WebDriverCommand;
use PhpWebDriver\WebDriver\Remote\WebDriverResponse;

/**
 * A HttpCommandExecutor that talks to a local driver service instead of a remote server.
 */
class DriverCommandExecutor extends HttpCommandExecutor
{
    /**
     * @var DriverService
     */
    private $service;

    public function __construct(DriverService $service)
    {
        parent::__construct($service->getURL());
        $this->service = $service;
    }

    /**
     * @param WebDriverCommand $command
     *
     * @throws WebDriverException
     * @throws \Exception
     * @return WebDriverResponse
     */
    public function execute(WebDriverCommand $command)
    {
        if ($command->getName() === DriverCommand::NEW_SESSION) {
            $this->service->start();
        }

        try {
            $value = parent::execute($command);
            if ($command->getName() === DriverCommand::QUIT) {
                $this->service->stop();
            }

            return $value;
        } catch (\Exception $e) {
            if (!$this->service->isRunning()) {
                throw new DriverServerDiedException($e);
            }
            throw $e;
        }
    }
}

class_alias(\PhpWebDriver\WebDriver\Remote\Service\DriverCommandExecutor::class, \Facebook\WebDriver\Remote\Service\DriverCommandExecutor::class);
