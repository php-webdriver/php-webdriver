<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\DesiredCapabilities::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\DesiredCapabilities" instead. */
    class DesiredCapabilities extends \PhpWebDriver\WebDriver\Remote\DesiredCapabilities
    {
    }
}
