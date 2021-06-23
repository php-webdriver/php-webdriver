<?php

namespace PhpWebDriver\WebDriver\Exception;

class WebDriverCurlException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\WebDriverCurlException::class, \Facebook\WebDriver\Exception\WebDriverCurlException::class);
