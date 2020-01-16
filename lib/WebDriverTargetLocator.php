<?php

namespace Facebook\WebDriver;

/**
 * Used to locate a given frame or window.
 */
interface WebDriverTargetLocator
{
    /**
     * Switch to the main document if the page contains iframes. Otherwise, switch
     * to the first frame on the page.
     *
     * @return WebDriver The driver focused on the top window or the first frame.
     */
    public function defaultContent();

    /**
     * Switch to the iframe by its id or name.
     *
     * @param WebDriverElement|string $frame The WebDriverElement,
     *                                       the id or the name of the frame.
     * @return WebDriver The driver focused on the given frame.
     */
    public function frame($frame);

    ///**
    // * Switch to the parent iframe.
    // *
    // * @todo Add in next major release (BC)
    // * @return WebDriver The driver focused on the given frame.
    // */
    //public function parent();

    /**
     * Switch the focus to another window by its handle.
     *
     * @param string $handle The handle of the window to be focused on.
     * @return WebDriver The driver focused on the given window.
     * @see WebDriver::getWindowHandles
     */
    public function window($handle);

    /**
     * Switch to the currently active modal dialog for this particular driver
     * instance.
     *
     * @return WebDriverAlert
     */
    public function alert();

    /**
     * Switches to the element that currently has focus within the document
     * currently "switched to", or the body element if this cannot be detected.
     *
     * @return WebDriverElement
     */
    public function activeElement();
}
