<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\SessionNotCreatedException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\SessionNotCreatedException" instead. */
    class SessionNotCreatedException extends \PhpWebDriver\WebDriver\Exception\SessionNotCreatedException
    {
    }
}
