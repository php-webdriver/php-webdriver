<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\ElementNotInteractableException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\ElementNotInteractableException" instead. */
    class ElementNotInteractableException extends \PhpWebDriver\WebDriver\Exception\ElementNotInteractableException
    {
    }
}
