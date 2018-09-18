<?php

namespace Facebook\WebDriver\Remote\Translator;

use Facebook\WebDriver\Remote\ExecutableWebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverCommand;

interface WebDriverProtocolTranslator
{
    /**
     * @param WebDriverCommand $command
     * @return ExecutableWebDriverCommand
     */
    public function translateCommand(WebDriverCommand $command);

    /**
     * @param array $raw_element
     * @return string
     */
    public function translateElement($raw_element);

    /**
     * @param string $command_name
     * @param array $params
     * @return array
     */
    public function translateParameters($command_name, $params);
}
