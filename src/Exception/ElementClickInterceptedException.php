<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\ElementClickInterceptedException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\ElementClickInterceptedException" instead. */
    class ElementClickInterceptedException extends \PhpWebDriver\WebDriver\Exception\ElementClickInterceptedException
    {
    }
}
