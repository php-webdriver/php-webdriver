<?php

namespace Facebook\WebDriver\Exception;

/**
 * The target for mouse interaction is not in the browser’s viewport and cannot be brought into that viewport.
 */
class MoveTargetOutOfBoundsException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\MoveTargetOutOfBoundsException', 'PhpWebDriver\Exception\MoveTargetOutOfBoundsException');
