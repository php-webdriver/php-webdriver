<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\WebDriverCommand::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\WebDriverCommand" instead. */
    class WebDriverCommand extends \PhpWebDriver\WebDriver\Remote\WebDriverCommand
    {
    }
}
