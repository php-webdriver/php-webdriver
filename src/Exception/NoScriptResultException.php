<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoScriptResultException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoScriptResultException" instead. */
    class NoScriptResultException extends \PhpWebDriver\WebDriver\Exception\NoScriptResultException
    {
    }
}
