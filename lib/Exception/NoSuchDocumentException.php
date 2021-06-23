<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Use PhpWebDriver\WebDriver\Exception\NoSuchWindowException
 */
class NoSuchDocumentException extends NoSuchWindowException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\NoSuchDocumentException::class, \Facebook\WebDriver\Exception\NoSuchDocumentException::class);
