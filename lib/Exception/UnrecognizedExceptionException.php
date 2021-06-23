<?php

namespace PhpWebDriver\WebDriver\Exception;

class UnrecognizedExceptionException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnrecognizedExceptionException::class, \Facebook\WebDriver\Exception\UnrecognizedExceptionException::class);
