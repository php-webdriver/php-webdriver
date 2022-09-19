<?php

namespace Facebook\WebDriver;

/**
 * Represent a dimension.
 */
class WebDriverDimension
{
    /**
     * @var int|float
     */
    private $height;
    /**
     * @var int|float
     */
    private $width;

    /**
     * @var int|float|null
     */
    private $x;

    /**
     * @var int|float|null
     */
    private $y;

    /**
     * @param int|float $width
     * @param int|float $height
     * @param int|float|null $x
     * @param int|float|null $y
     */
    public function __construct($width, $height, $x = null, $y = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Get the height.
     *
     * @return int The height.
     */
    public function getHeight()
    {
        return (int) $this->height;
    }

    /**
     * Get the width.
     *
     * @return int The width.
     */
    public function getWidth()
    {
        return (int) $this->width;
    }

    /**
     * Check whether the given dimension is the same as the instance.
     *
     * @param WebDriverDimension $dimension The dimension to be compared with.
     * @return bool Whether the height and the width are the same as the instance.
     */
    public function equals(self $dimension)
    {
        return $this->height === $dimension->getHeight() && $this->width === $dimension->getWidth();
    }

    /**
     * @return float|int|null
     */
    public function getScreenX()
    {
        return $this->x;
    }

    /**
     * @return float|int|null
     */
    public function getScreenY()
    {
        return $this->y;
    }
}
