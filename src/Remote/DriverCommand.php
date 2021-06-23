<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\DriverCommand::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\DriverCommand" instead. */
    class DriverCommand extends \PhpWebDriver\WebDriver\Remote\DriverCommand
    {
    }
}
