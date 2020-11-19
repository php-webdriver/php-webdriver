<?php

namespace Facebook\WebDriver\Chrome;

/**
 * Constants defining chrome-specific commands
 *
 * @codeCoverageIgnore
 */
class ChromeDriverCommand
{
    // Chrome DevTools API
    const SEND_COMMAND = 'sendCommand';
    const SEND_COMMAND_AND_GET_RESULT = 'sendCommandAndGetResult';

    private function __construct()
    {
    }
}
