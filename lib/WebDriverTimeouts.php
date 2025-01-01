<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecuteMethod;

/**
 * Managing timeout behavior for WebDriver instances.
 */
class WebDriverTimeouts
{
    /**
     * @var ExecuteMethod
     */
    protected $executor;
    /**
     * @var bool
     */
    protected $isW3cCompliant;

    public function __construct(ExecuteMethod $executor, $isW3cCompliant = false)
    {
        $this->executor = $executor;
        $this->isW3cCompliant = $isW3cCompliant;
    }

    /**
     * Specify the amount of time the driver should wait when searching for an element if it is not immediately present.
     *
     * @param null|int|float $seconds Wait time in seconds.
     * @return WebDriverTimeouts The current instance.
     */
    public function implicitlyWait($seconds)
    {
        if ($this->isW3cCompliant) {
            $this->executor->execute(
                DriverCommand::IMPLICITLY_WAIT,
                ['implicit' => $seconds === null ? null : floor($seconds * 1000)]
            );

            return $this;
        }

        if ($seconds === null) {
            throw new \InvalidArgumentException('JsonWire Protocol implicit-wait-timeout value cannot be null');
        }
        $this->executor->execute(
            DriverCommand::IMPLICITLY_WAIT,
            ['ms' => floor($seconds * 1000)]
        );

        return $this;
    }

    /**
     * Set the amount of time to wait for an asynchronous script to finish execution before throwing an error.
     *
     * @param null|int|float $seconds Wait time in seconds.
     * @return WebDriverTimeouts The current instance.
     */
    public function setScriptTimeout($seconds)
    {
        if ($this->isW3cCompliant) {
            $this->executor->execute(
                DriverCommand::SET_SCRIPT_TIMEOUT,
                ['script' => $seconds === null ? null : floor($seconds * 1000)]
            );

            return $this;
        }

        if ($seconds === null) {
            throw new \InvalidArgumentException('JsonWire Protocol script-timeout value cannot be null');
        }
        $this->executor->execute(
            DriverCommand::SET_SCRIPT_TIMEOUT,
            ['ms' => floor($seconds * 1000)]
        );

        return $this;
    }

    /**
     * Set the amount of time to wait for a page load to complete before throwing an error.
     *
     * @param null|int|float $seconds Wait time in seconds.
     * @return WebDriverTimeouts The current instance.
     */
    public function pageLoadTimeout($seconds)
    {
        if ($this->isW3cCompliant) {
            $this->executor->execute(
                DriverCommand::SET_SCRIPT_TIMEOUT,
                ['pageLoad' => $seconds === null ? null : floor($seconds * 1000)]
            );

            return $this;
        }

        if ($seconds === null) {
            throw new \InvalidArgumentException('JsonWire Protocol page-load-timeout value cannot be null');
        }
        $this->executor->execute(DriverCommand::SET_TIMEOUT, [
            'type' => 'page load',
            'ms' => floor($seconds * 1000),
        ]);

        return $this;
    }
}
