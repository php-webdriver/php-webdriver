<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverMouse;

/**
 * Base class for all mouse-related actions.
 */
class WebDriverMouseAction
{
    /**
     * @var WebDriverMouse
     */
    protected $mouse;
    /**
     * @var WebDriverLocatable
     */
    protected $locationProvider;

    /**
     * @param WebDriverMouse $mouse
     * @param WebDriverLocatable|null $location_provider
     */
    public function __construct(WebDriverMouse $mouse, WebDriverLocatable $location_provider = null)
    {
        $this->mouse = $mouse;
        $this->locationProvider = $location_provider;
    }

    /**
     * @return null|WebDriverCoordinates
     */
    protected function getActionLocation()
    {
        if ($this->locationProvider !== null) {
            return $this->locationProvider->getCoordinates();
        }

        return null;
    }

    protected function moveToLocation()
    {
        $this->mouse->mouseMove($this->locationProvider);
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverMouseAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverMouseAction::class);
