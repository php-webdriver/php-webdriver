<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Exception\UnsupportedOperationException;
use PhpWebDriver\WebDriver\Exception\WebDriverException;

/**
 * Provides helper methods for radio buttons.
 */
class WebDriverRadios extends AbstractWebDriverCheckboxOrRadio
{
    public function __construct(WebDriverElement $element)
    {
        parent::__construct($element);

        $this->type = $element->getAttribute('type');
        if ($this->type !== 'radio') {
            throw new WebDriverException('The input must be of type "radio".');
        }
    }

    public function isMultiple()
    {
        return false;
    }

    public function deselectAll()
    {
        throw new UnsupportedOperationException('You cannot deselect radio buttons');
    }

    public function deselectByIndex($index)
    {
        throw new UnsupportedOperationException('You cannot deselect radio buttons');
    }

    public function deselectByValue($value)
    {
        throw new UnsupportedOperationException('You cannot deselect radio buttons');
    }

    public function deselectByVisibleText($text)
    {
        throw new UnsupportedOperationException('You cannot deselect radio buttons');
    }

    public function deselectByVisiblePartialText($text)
    {
        throw new UnsupportedOperationException('You cannot deselect radio buttons');
    }
}

class_alias(\PhpWebDriver\WebDriver\WebDriverRadios::class, \Facebook\WebDriver\WebDriverRadios::class);
