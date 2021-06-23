<?php

namespace Facebook\WebDriver\Remote;

class_exists(\PhpWebDriver\WebDriver\Remote\JsonWireCompat::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Remote\JsonWireCompat" instead. */
    class JsonWireCompat extends \PhpWebDriver\WebDriver\Remote\JsonWireCompat
    {
    }
}
