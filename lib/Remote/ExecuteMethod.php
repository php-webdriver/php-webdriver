<?php

namespace PhpWebDriver\WebDriver\Remote;

interface ExecuteMethod
{
    /**
     * @param string $command_name
     * @param array $parameters
     * @return WebDriverResponse
     */
    public function execute($command_name, array $parameters = []);
}

class_alias(\PhpWebDriver\WebDriver\Remote\ExecuteMethod::class, \Facebook\WebDriver\Remote\ExecuteMethod::class);
