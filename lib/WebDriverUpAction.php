<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverTouchAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverTouchScreen;

class WebDriverUpAction extends WebDriverTouchAction implements WebDriverAction
{
    private $x;
    private $y;

    /**
     * @param WebDriverTouchScreen $touch_screen
     * @param int $x
     * @param int $y
     */
    public function __construct(WebDriverTouchScreen $touch_screen, $x, $y)
    {
        $this->x = $x;
        $this->y = $y;
        parent::__construct($touch_screen);
    }

    public function perform()
    {
        $this->touchScreen->up($this->x, $this->y);
    }
}

class_alias(\PhpWebDriver\WebDriver\WebDriverUpAction::class, \Facebook\WebDriver\WebDriverUpAction::class);
