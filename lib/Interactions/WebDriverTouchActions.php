<?php

namespace PhpWebDriver\WebDriver\Interactions;

use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverDoubleTapAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverDownAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverFlickAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverFlickFromElementAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverLongPressAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverMoveAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverScrollAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverScrollFromElementAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverTapAction;
use PhpWebDriver\WebDriver\Interactions\Touch\WebDriverTouchScreen;
use PhpWebDriver\WebDriver\WebDriver;
use PhpWebDriver\WebDriver\WebDriverElement;
use PhpWebDriver\WebDriver\WebDriverUpAction;

/**
 * WebDriver action builder for touch events
 */
class WebDriverTouchActions extends WebDriverActions
{
    /**
     * @var WebDriverTouchScreen
     */
    protected $touchScreen;

    public function __construct(WebDriver $driver)
    {
        parent::__construct($driver);
        $this->touchScreen = $driver->getTouch();
    }

    /**
     * @param WebDriverElement $element
     * @return WebDriverTouchActions
     */
    public function tap(WebDriverElement $element)
    {
        $this->action->addAction(
            new WebDriverTapAction($this->touchScreen, $element)
        );

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @return WebDriverTouchActions
     */
    public function down($x, $y)
    {
        $this->action->addAction(
            new WebDriverDownAction($this->touchScreen, $x, $y)
        );

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @return WebDriverTouchActions
     */
    public function up($x, $y)
    {
        $this->action->addAction(
            new WebDriverUpAction($this->touchScreen, $x, $y)
        );

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @return WebDriverTouchActions
     */
    public function move($x, $y)
    {
        $this->action->addAction(
            new WebDriverMoveAction($this->touchScreen, $x, $y)
        );

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @return WebDriverTouchActions
     */
    public function scroll($x, $y)
    {
        $this->action->addAction(
            new WebDriverScrollAction($this->touchScreen, $x, $y)
        );

        return $this;
    }

    /**
     * @param WebDriverElement $element
     * @param int $x
     * @param int $y
     * @return WebDriverTouchActions
     */
    public function scrollFromElement(WebDriverElement $element, $x, $y)
    {
        $this->action->addAction(
            new WebDriverScrollFromElementAction($this->touchScreen, $element, $x, $y)
        );

        return $this;
    }

    /**
     * @param WebDriverElement $element
     * @return WebDriverTouchActions
     */
    public function doubleTap(WebDriverElement $element)
    {
        $this->action->addAction(
            new WebDriverDoubleTapAction($this->touchScreen, $element)
        );

        return $this;
    }

    /**
     * @param WebDriverElement $element
     * @return WebDriverTouchActions
     */
    public function longPress(WebDriverElement $element)
    {
        $this->action->addAction(
            new WebDriverLongPressAction($this->touchScreen, $element)
        );

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @return WebDriverTouchActions
     */
    public function flick($x, $y)
    {
        $this->action->addAction(
            new WebDriverFlickAction($this->touchScreen, $x, $y)
        );

        return $this;
    }

    /**
     * @param WebDriverElement $element
     * @param int $x
     * @param int $y
     * @param int $speed
     * @return WebDriverTouchActions
     */
    public function flickFromElement(WebDriverElement $element, $x, $y, $speed)
    {
        $this->action->addAction(
            new WebDriverFlickFromElementAction(
                $this->touchScreen,
                $element,
                $x,
                $y,
                $speed
            )
        );

        return $this;
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\WebDriverTouchActions::class, \Facebook\WebDriver\Interactions\WebDriverTouchActions::class);
