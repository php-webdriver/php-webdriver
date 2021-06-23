<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * A command could not be completed because the element is not pointer- or keyboard interactable.
 */
class ElementNotInteractableException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\ElementNotInteractableException::class, \Facebook\WebDriver\Exception\ElementNotInteractableException::class);
