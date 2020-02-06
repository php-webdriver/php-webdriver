<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverAlert;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverTargetLocator;

/**
 * Used to locate a given frame or window for RemoteWebDriver.
 */
class RemoteTargetLocator implements WebDriverTargetLocator
{
    /** @var ExecuteMethod */
    protected $executor;
    /** @var WebDriver */
    protected $driver;
    /** @var bool */
    protected $isW3cCompliant;

    public function __construct($executor, $driver, $isW3cCompliant = false)
    {
        $this->executor = $executor;
        $this->driver = $driver;
        $this->isW3cCompliant = $isW3cCompliant;
    }

    /**
     * Set the current browsing context to the current top-level browsing context.
     * This is the same as calling `RemoteTargetLocator::frame(null);`
     *
     * @return WebDriver The driver focused on the top window or the first frame.
     */
    public function defaultContent()
    {
        $params = ['id' => null];
        $this->executor->execute(DriverCommand::SWITCH_TO_FRAME, $params);

        return $this->driver;
    }

    /**
     * Switch to the iframe by its id or name.
     *
     * @param WebDriverElement|null|int|string $frame The WebDriverElement,
     * the id or the name of the frame.
     * When null, switch to the current top-level browsing context
     * When int, switch to the WindowProxy identified by the value
     * When an Element, switch to that Element.
     *
     * @throws \InvalidArgumentException
     * @return WebDriver The driver focused on the given frame.
     */
    public function frame($frame)
    {
        if ($this->isW3cCompliant) {
            if ($frame instanceof WebDriverElement) {
                $id = [JsonWireCompat::WEB_DRIVER_ELEMENT_IDENTIFIER => $frame->getID()];
            } elseif ($frame === null) {
                $id = null;
            } elseif (is_int($frame)) {
                $id = $frame;
            } else {
                throw new \InvalidArgumentException(
                    'In W3C compliance mode frame must be either instance of WebDriverElement, integer or null'
                );
            }
        } else {
            if ($frame instanceof WebDriverElement) {
                $id = ['ELEMENT' => $frame->getID()];
            } elseif ($frame === null) {
                $id = null;
            } elseif (is_int($frame)) {
                $id = $frame;
            } else {
                $id = (string) $frame;
            }
        }

        $params = ['id' => $id];
        $this->executor->execute(DriverCommand::SWITCH_TO_FRAME, $params);

        return $this->driver;
    }

    /**
     * Switch to the parent iframe.
     *
     * @return WebDriver The driver focused on the given frame.
     */
    public function parent()
    {
        $this->executor->execute(DriverCommand::SWITCH_TO_PARENT_FRAME, []);

        return $this->driver;
    }

    /**
     * Switch the focus to another window by its handle.
     *
     * @param string $handle The handle of the window to be focused on.
     * @return WebDriver The driver focused on the given window.
     * @see WebDriver::getWindowHandles
     */
    public function window($handle)
    {
        if ($this->isW3cCompliant) {
            $params = ['handle' => (string) $handle];
        } else {
            $params = ['name' => (string) $handle];
        }

        $this->executor->execute(DriverCommand::SWITCH_TO_WINDOW, $params);

        return $this->driver;
    }

    /**
     * Switch to the currently active modal dialog for this particular driver
     * instance.
     *
     * @return WebDriverAlert
     */
    public function alert()
    {
        return new WebDriverAlert($this->executor);
    }

    /**
     * Switches to the element that currently has focus within the document
     * currently "switched to", or the body element if this cannot be detected.
     *
     * @return RemoteWebElement
     */
    public function activeElement()
    {
        $response = $this->driver->execute(DriverCommand::GET_ACTIVE_ELEMENT, []);
        $method = new RemoteExecuteMethod($this->driver);

        return new RemoteWebElement($method, JsonWireCompat::getElement($response), $this->isW3cCompliant);
    }
}
