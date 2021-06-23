<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\NoSuchFrameException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\NoSuchFrameException" instead. */
    class NoSuchFrameException extends \PhpWebDriver\WebDriver\Exception\NoSuchFrameException
    {
    }
}
