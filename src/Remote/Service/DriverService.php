<?php

namespace Facebook\WebDriver\Remote\Service;

class_exists(\PhpWebDriver\WebDriver\Remote\Service\DriverService::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\Service\DriverService" instead. */
    class DriverService extends \PhpWebDriver\WebDriver\Remote\Service\DriverService
    {
    }
}
