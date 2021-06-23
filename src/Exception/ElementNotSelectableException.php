<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\ElementNotSelectableException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\ElementNotSelectableException" instead. */
    class ElementNotSelectableException extends \PhpWebDriver\WebDriver\Exception\ElementNotSelectableException
    {
    }
}
