<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\DriverServerDiedException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\DriverServerDiedException" instead. */
    class DriverServerDiedException extends \PhpWebDriver\WebDriver\Exception\DriverServerDiedException
    {
    }
}
