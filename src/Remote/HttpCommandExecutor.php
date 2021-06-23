<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\HttpCommandExecutor::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\HttpCommandExecutor" instead. */
    class HttpCommandExecutor extends \PhpWebDriver\WebDriver\Remote\HttpCommandExecutor
    {
    }
}
