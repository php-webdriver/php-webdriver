<?php

namespace PhpWebDriver\WebDriver\Remote;

class RemoteExecuteMethod implements ExecuteMethod
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;

    /**
     * @param RemoteWebDriver $driver
     */
    public function __construct(RemoteWebDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $command_name
     * @param array $parameters
     * @return mixed
     */
    public function execute($command_name, array $parameters = [])
    {
        return $this->driver->execute($command_name, $parameters);
    }
}

class_alias(\PhpWebDriver\WebDriver\Remote\RemoteExecuteMethod::class, \Facebook\WebDriver\Remote\RemoteExecuteMethod::class);
