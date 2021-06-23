<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * The target for mouse interaction is not in the browser’s viewport and cannot be brought into that viewport.
 */
class MoveTargetOutOfBoundsException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\MoveTargetOutOfBoundsException::class, \Facebook\WebDriver\Exception\MoveTargetOutOfBoundsException::class);
