<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InvalidCoordinatesException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InvalidCoordinatesException" instead. */
    class InvalidCoordinatesException extends \PhpWebDriver\WebDriver\Exception\InvalidCoordinatesException
    {
    }
}
