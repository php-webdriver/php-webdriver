<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\IndexOutOfBoundsException;
use Facebook\WebDriver\Exception\UnsupportedOperationException;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecuteMethod;

/**
 * An abstraction allowing the driver to manipulate the browser's window
 */
class WebDriverWindow
{
    /**
     * @var ExecuteMethod
     */
    protected $executor;
    /**
     * @var bool
     */
    protected $isW3cCompliant;

    public function __construct(ExecuteMethod $executor, $isW3cCompliant = false)
    {
        $this->executor = $executor;
        $this->isW3cCompliant = $isW3cCompliant;
    }

    /**
     * Get the position of the current window, relative to the upper left corner
     * of the screen.
     *
     * @return WebDriverPoint The current window position.
     */
    public function getPosition()
    {
        $position = $this->executor->execute(
            DriverCommand::GET_WINDOW_POSITION,
            [':windowHandle' => 'current']
        );

        return new WebDriverPoint(
            $position['x'],
            $position['y']
        );
    }

    /**
     * Get the size of the current window. This will return the outer window
     * dimension, not just the view port.
     *
     * @return WebDriverDimension The current window size.
     */
    public function getSize()
    {
        $size = $this->executor->execute(
            DriverCommand::GET_WINDOW_SIZE,
            [':windowHandle' => 'current']
        );

        if (!$this->isW3cCompliant) {
            $dimension = $this->getPosition();
            $dimensionArray = ['x' => $dimension->getX(), 'y' => $dimension->getY()];
        } else {
            $dimensionArray = ['x' => $size['x'], 'y' => $size['y']];
        }

        return new WebDriverDimension(
            $size['width'],
            $size['height'],
            $dimensionArray['x'],
            $dimensionArray['y']
        );
    }

    /**
     * Minimizes the current window if it is not already minimized.
     *
     * @return WebDriverDimension
     */
    public function minimize()
    {
        if (!$this->isW3cCompliant) {
            throw new UnsupportedOperationException('Minimize window is only supported in W3C mode');
        }

        $size = $this->executor->execute(DriverCommand::MINIMIZE_WINDOW, []);

        return new WebDriverDimension(
            $size['width'],
            $size['height'],
            $size['x'],
            $size['y']
        );
    }

    /**
     * Maximizes the current window if it is not already maximized
     *
     * @return WebDriverDimension
     */
    public function maximize()
    {
        if ($this->isW3cCompliant) {
            $size = $this->executor->execute(DriverCommand::MAXIMIZE_WINDOW, []);
        } else {
            $size = $this->executor->execute(
                DriverCommand::MAXIMIZE_WINDOW,
                [':windowHandle' => 'current']
            );
        }

        return new WebDriverDimension(
            $size['width'],
            $size['height'],
            $size['x'],
            $size['y']
        );
    }

    /**
     * Makes the current window full screen.
     *
     * @return WebDriverDimension
     */
    public function fullscreen()
    {
        if (!$this->isW3cCompliant) {
            throw new UnsupportedOperationException('The Fullscreen window command is only supported in W3C mode');
        }

        $size = $this->executor->execute(DriverCommand::FULLSCREEN_WINDOW, []);

        return new WebDriverDimension(
            $size['width'],
            $size['height'],
            $size['x'],
            $size['y']
        );
    }

    /**
     * Set the size of the current window. This will change the outer window
     * dimension, not just the view port.
     *
     * @param WebDriverDimension $size
     *
     * @return WebDriverDimension
     */
    public function setSize(WebDriverDimension $size)
    {
        $params = [
            'width' => $size->getWidth(),
            'height' => $size->getHeight(),
            ':windowHandle' => 'current',
        ];

        $size = $this->executor->execute(DriverCommand::SET_WINDOW_SIZE, $params);

        if (!$this->isW3cCompliant) {
            return $this->getSize();
        }

        return new WebDriverDimension(
            $size['width'],
            $size['height'],
            $size['x'],
            $size['y']
        );
    }

    /**
     * Set the position of the current window. This is relative to the upper left
     * corner of the screen.
     *
     * @param WebDriverPoint $position
     *
     * @return WebDriverDimension
     */
    public function setPosition(WebDriverPoint $position)
    {
        $params = [
            'x' => $position->getX(),
            'y' => $position->getY(),
            ':windowHandle' => 'current',
        ];

        $size = $this->executor->execute(DriverCommand::SET_WINDOW_POSITION, $params);

        if (!$this->isW3cCompliant) {
            return $this->getSize();
        }

        return new WebDriverDimension(
            $size['width'],
            $size['height'],
            $size['x'],
            $size['y']
        );
    }

    /**
     * Get the current browser orientation.
     *
     * @return string Either LANDSCAPE|PORTRAIT
     */
    public function getScreenOrientation()
    {
        if ($this->isW3cCompliant) {
            throw new UnsupportedOperationException(
                'The Screen Orientation window command is only supported in OSS mode'
            );
        }

        return $this->executor->execute(DriverCommand::GET_SCREEN_ORIENTATION);
    }

    /**
     * Set the browser orientation. The orientation should either
     * LANDSCAPE|PORTRAIT
     *
     * @param string $orientation
     * @throws IndexOutOfBoundsException
     * @return WebDriverWindow The instance.
     */
    public function setScreenOrientation($orientation)
    {
        if ($this->isW3cCompliant) {
            throw new UnsupportedOperationException(
                'The Screen Orientation window command is only supported in OSS mode'
            );
        }

        $orientation = mb_strtoupper($orientation);
        if (!in_array($orientation, ['PORTRAIT', 'LANDSCAPE'])) {
            throw new IndexOutOfBoundsException(
                'Orientation must be either PORTRAIT, or LANDSCAPE'
            );
        }

        $this->executor->execute(
            DriverCommand::SET_SCREEN_ORIENTATION,
            ['orientation' => $orientation]
        );

        return $this;
    }
}
