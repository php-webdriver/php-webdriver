<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\RemoteWebElement::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\RemoteWebElement" instead. */
    class RemoteWebElement extends \PhpWebDriver\WebDriver\Remote\RemoteWebElement
    {
    }
}
