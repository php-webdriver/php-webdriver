<?php

namespace PhpWebDriver\WebDriver\Interactions\Touch;

use PhpWebDriver\WebDriver\WebDriverAction;
use PhpWebDriver\WebDriver\WebDriverElement;

class WebDriverScrollFromElementAction extends WebDriverTouchAction implements WebDriverAction
{
    private $x;
    private $y;

    /**
     * @param WebDriverTouchScreen $touch_screen
     * @param WebDriverElement $element
     * @param int $x
     * @param int $y
     */
    public function __construct(
        WebDriverTouchScreen $touch_screen,
        WebDriverElement $element,
        $x,
        $y
    ) {
        $this->x = $x;
        $this->y = $y;
        parent::__construct($touch_screen, $element);
    }

    public function perform()
    {
        $this->touchScreen->scrollFromElement(
            $this->locationProvider,
            $this->x,
            $this->y
        );
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Touch\WebDriverScrollFromElementAction::class, \Facebook\WebDriver\Interactions\Touch\WebDriverScrollFromElementAction::class);
