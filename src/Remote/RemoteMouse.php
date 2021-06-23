<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\RemoteMouse::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\RemoteMouse" instead. */
    class RemoteMouse extends \PhpWebDriver\WebDriver\Remote\RemoteMouse
    {
    }
}
