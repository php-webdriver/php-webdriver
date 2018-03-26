<?php

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\Service\DriverCommandExecutor;
use Facebook\WebDriver\Remote\Service\DriverService;

class ChromeDriverCommandExecutor extends DriverCommandExecutor
{
    public function __construct(DriverService $service)
    {
        parent::__construct($service);

        self::$commands = array_merge(self::$commands, [
            ChromeDriverCommand::SEND_COMMAND => [
                'method' => 'POST',
                'url' => '/session/:sessionId/chromium/send_command',
            ],
            ChromeDriverCommand::SEND_COMMAND_AND_GET_RESULT => [
                'method' => 'POST',
                'url' => '/session/:sessionId/chromium/send_command_and_get_result',
            ],
        ]);
    }
}
