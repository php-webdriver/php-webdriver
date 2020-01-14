<?php

namespace Facebook\WebDriver;

/**
 * The platforms supported by WebDriver.
 *
 * @codeCoverageIgnore
 */
class WebDriverPlatform
{
    const ANDROID = 'ANDROID';
    /** @deprecated */
    const ANY = 'ANY';
    const LINUX = 'LINUX';
    const MAC = 'MAC';
    const UNIX = 'UNIX';
    const VISTA = 'VISTA';
    const WINDOWS = 'WINDOWS';
    const XP = 'XP';

    private function __construct()
    {
    }
}
