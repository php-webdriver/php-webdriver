<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * @deprecated Use PhpWebDriver\WebDriver\Exception\UnknownErrorException
 */
class UnknownServerException extends UnknownErrorException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\UnknownServerException::class, \Facebook\WebDriver\Exception\UnknownServerException::class);
