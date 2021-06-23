<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\RemoteWebDriver::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\RemoteWebDriver" instead. */
    class RemoteWebDriver extends \PhpWebDriver\WebDriver\Remote\RemoteWebDriver
    {
    }
}
