<?php

namespace Facebook\WebDriver;

/**
 * @covers \Facebook\WebDriver\WebDriverWindow
 */
class WebDriverWindowTest extends WebDriverTestCase
{
    public function testShouldGetPosition()
    {
        $position = $this->driver->manage()
            ->window()
            ->getPosition();

        $this->assertGreaterThanOrEqual(0, $position->getX());
        $this->assertGreaterThanOrEqual(0, $position->getY());
    }

    public function testShouldGetSize()
    {
        $size = $this->driver->manage()
            ->window()
            ->getSize();

        $this->assertGreaterThan(0, $size->getWidth());
        $this->assertGreaterThan(0, $size->getHeight());
    }

    public function testShouldMaximizeWindow()
    {
        $sizeBefore = $this->driver->manage()
            ->window()
            ->getSize();

        $this->driver->manage()
            ->window()
            ->maximize();

        $sizeAfter = $this->driver->manage()
            ->window()
            ->getSize();

        $this->assertGreaterThanOrEqual($sizeBefore->getWidth(), $sizeAfter->getWidth());
        $this->assertGreaterThanOrEqual($sizeBefore->getHeight(), $sizeAfter->getHeight());
    }

    public function testShouldSetSize()
    {
        $sizeBefore = $this->driver->manage()
            ->window()
            ->getSize();
        $this->assertNotSame(500, $sizeBefore->getWidth());
        $this->assertNotSame(666, $sizeBefore->getHeight());

        $this->driver->manage()
            ->window()
            ->setSize(new WebDriverDimension(500, 666));

        $sizeAfter = $this->driver->manage()
            ->window()
            ->getSize();

        $this->assertSame(500, $sizeAfter->getWidth());
        $this->assertSame(666, $sizeAfter->getHeight());
    }

    public function testShouldSetWindowPosition()
    {
        $this->driver->manage()
            ->window()
            ->setPosition(new WebDriverPoint(33, 66));

        $positionAfter = $this->driver->manage()
            ->window()
            ->getPosition();

        $this->assertSame(33, $positionAfter->getX());
        $this->assertSame(66, $positionAfter->getY());
    }
}
