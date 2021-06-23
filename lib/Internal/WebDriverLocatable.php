<?php

namespace PhpWebDriver\WebDriver\Internal;

use PhpWebDriver\WebDriver\Interactions\Internal\WebDriverCoordinates;

/**
 * Interface representing basic mouse operations.
 */
interface WebDriverLocatable
{
    /**
     * @return WebDriverCoordinates
     */
    public function getCoordinates();
}
