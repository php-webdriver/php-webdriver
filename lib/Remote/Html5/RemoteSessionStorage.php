<?php

namespace Facebook\WebDriver\Remote\Html5;

use Facebook\WebDriver\Html5\SessionStorageInterface;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;

/**
 * Executes the commands to access HTML5 sessionStorage on the remote webdriver server.
 */
class RemoteSessionStorage implements SessionStorageInterface
{
    /**
     * @var RemoteExecuteMethod
     */
    private $executor;

    /**
     * @param RemoteExecuteMethod $executor
     */
    public function __construct(RemoteExecuteMethod $executor)
    {
        $this->executor = $executor;
    }

    public function clear()
    {
        $this->executor->execute(DriverCommand::CLEAR_SESSION_STORAGE);
    }

    public function getItem($key)
    {
        return $this->executor->execute(DriverCommand::GET_SESSION_STORAGE_ITEM, [
            ':key' => $key,
        ]);
    }

    public function keySet()
    {
        return $this->executor->execute(DriverCommand::GET_SESSION_STORAGE_KEYS);
    }

    public function removeItem($key)
    {
        $this->executor->execute(DriverCommand::REMOVE_SESSION_STORAGE_ITEM, [
            ':key' => $key,
        ]);
    }

    public function setItem($key, $value)
    {
        $this->executor->execute(DriverCommand::SET_SESSION_STORAGE_ITEM, [
            'key' => $key,
            'value' => $value,
        ]);
    }

    public function size()
    {
        return $this->executor->execute(DriverCommand::GET_SESSION_STORAGE_SIZE);
    }
}
