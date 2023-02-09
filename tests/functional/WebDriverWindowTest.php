<?php declare(strict_types=1);

namespace Facebook\WebDriver;

/**
 * @covers \Facebook\WebDriver\WebDriverWindow
 */
class WebDriverWindowTest extends WebDriverTestCase
{
    /**
     * @group exclude-saucelabs
     */
    public function testShouldGetPosition(): void
    {
        $position = $this->driver->manage()
            ->window()
            ->getPosition();

        $this->assertGreaterThanOrEqual(0, $position->getX());
        $this->assertGreaterThanOrEqual(0, $position->getY());
    }

    public function testShouldGetSize(): void
    {
        $size = $this->driver->manage()
            ->window()
            ->getSize();

        $this->assertGreaterThan(0, $size->getWidth());
        $this->assertGreaterThan(0, $size->getHeight());
    }

    public function testShouldMaximizeWindow(): void
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

    /**
     * @group exclude-edge
     * @group exclude-saucelabs
     */
    public function testShouldFullscreenWindow(): void
    {
        self::skipForJsonWireProtocol('"fullscreen" window is not supported in JsonWire protocol');

        $this->driver->manage()
            ->window()
            ->setSize(new WebDriverDimension(400, 300));

        $this->driver->manage()
            ->window()
            ->fullscreen();

        $sizeAfter = $this->driver->manage()
            ->window()
            ->getSize();

        // Note: Headless browsers see no effect.
        $this->assertGreaterThanOrEqual(400, $sizeAfter->getWidth());
        $this->assertGreaterThanOrEqual(300, $sizeAfter->getHeight());
    }

    /**
     * @see https://bugs.chromium.org/p/chromium/issues/detail?id=1038050
     * @group exclude-chrome
     * @group exclude-safari
     * @group exclude-saucelabs
     */
    public function testShouldMinimizeWindow(): void
    {
        self::skipForJsonWireProtocol('"minimize" window is not supported in JsonWire protocol');

        $this->assertSame('visible', $this->driver->executeScript('return document.visibilityState;'));

        $this->driver->manage()
            ->window()
            ->minimize();

        $this->assertSame('hidden', $this->driver->executeScript('return document.visibilityState;'));
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldSetSize(): void
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

    /**
     * @todo Skip when running headless mode
     */
    public function testShouldSetWindowPosition(): void
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
