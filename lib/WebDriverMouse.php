<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Interactions\Internal\WebDriverCoordinates;

/**
 * Interface representing basic mouse operations.
 */
interface WebDriverMouse
{
    /**
     * @param WebDriverCoordinates $where
     * @return WebDriverMouse
     */
    public function click(WebDriverCoordinates $where);

    /**
     * @param WebDriverCoordinates $where
     * @return WebDriverMouse
     */
    public function contextClick(WebDriverCoordinates $where);

    /**
     * @param WebDriverCoordinates $where
     * @return WebDriverMouse
     */
    public function doubleClick(WebDriverCoordinates $where);

    /**
     * @param WebDriverCoordinates $where
     * @return WebDriverMouse
     */
    public function mouseDown(WebDriverCoordinates $where);

    /**
     * @param WebDriverCoordinates $where
     * @param int $x_offset
     * @param int $y_offset
     * @return WebDriverMouse
     */
    public function mouseMove(
        WebDriverCoordinates $where,
        $x_offset = null,
        $y_offset = null
    );

    /**
     * @param WebDriverCoordinates $where
     * @return WebDriverMouse
     */
    public function mouseUp(WebDriverCoordinates $where);
}

class_alias(\PhpWebDriver\WebDriver\WebDriverMouse::class, \Facebook\WebDriver\WebDriverMouse::class);
