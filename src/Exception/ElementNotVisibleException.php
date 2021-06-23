<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\ElementNotVisibleException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\ElementNotVisibleException" instead. */
    class ElementNotVisibleException extends \PhpWebDriver\WebDriver\Exception\ElementNotVisibleException
    {
    }
}
