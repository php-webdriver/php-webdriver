<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Use PhpWebDriver\WebDriver\Exception\ElementNotInteractableException
 */
class ElementNotSelectableException extends ElementNotInteractableException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\ElementNotSelectableException::class, \Facebook\WebDriver\Exception\ElementNotSelectableException::class);
