<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverAction;
use PhpWebDriver\WebDriver\WebDriverMouse;

class WebDriverMoveToOffsetAction extends WebDriverMouseAction implements WebDriverAction
{
    /**
     * @var int|null
     */
    private $xOffset;
    /**
     * @var int|null
     */
    private $yOffset;

    /**
     * @param WebDriverMouse $mouse
     * @param WebDriverLocatable|null $location_provider
     * @param int|null $x_offset
     * @param int|null $y_offset
     */
    public function __construct(
        WebDriverMouse $mouse,
        WebDriverLocatable $location_provider = null,
        $x_offset = null,
        $y_offset = null
    ) {
        parent::__construct($mouse, $location_provider);
        $this->xOffset = $x_offset;
        $this->yOffset = $y_offset;
    }

    public function perform()
    {
        $this->mouse->mouseMove(
            $this->getActionLocation(),
            $this->xOffset,
            $this->yOffset
        );
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverMoveToOffsetAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverMoveToOffsetAction::class);
