<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\ExpectedException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\ExpectedException" instead. */
    class ExpectedException extends \PhpWebDriver\WebDriver\Exception\ExpectedException
    {
    }
}
