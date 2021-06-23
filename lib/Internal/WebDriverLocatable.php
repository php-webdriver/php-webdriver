<?php

namespace Facebook\WebDriver\Internal;

use Facebook\WebDriver\Interactions\Internal\WebDriverCoordinates;

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

class_alias('Facebook\WebDriver\Internal\WebDriverLocatable', 'PhpWebDriver\Internal\WebDriverLocatable');
