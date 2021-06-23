<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\WebDriverElement::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\WebDriverElement" instead. */
    class WebDriverElement extends \PhpWebDriver\WebDriver\WebDriverElement
    {
    }
}
