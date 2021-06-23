<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Remote\DriverCommand;
use PhpWebDriver\WebDriver\Remote\ExecuteMethod;

class WebDriverNavigation implements WebDriverNavigationInterface
{
    protected $executor;

    public function __construct(ExecuteMethod $executor)
    {
        $this->executor = $executor;
    }

    public function back()
    {
        $this->executor->execute(DriverCommand::GO_BACK);

        return $this;
    }

    public function forward()
    {
        $this->executor->execute(DriverCommand::GO_FORWARD);

        return $this;
    }

    public function refresh()
    {
        $this->executor->execute(DriverCommand::REFRESH);

        return $this;
    }

    public function to($url)
    {
        $params = ['url' => (string) $url];
        $this->executor->execute(DriverCommand::GET, $params);

        return $this;
    }
}

class_alias(\PhpWebDriver\WebDriver\WebDriverNavigation::class, \Facebook\WebDriver\WebDriverNavigation::class);
