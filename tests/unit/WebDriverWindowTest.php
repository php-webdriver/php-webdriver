<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\IndexOutOfBoundsException;
use Facebook\WebDriver\Exception\UnsupportedOperationException;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecuteMethod;
use PHPUnit\Framework\TestCase;

class WebDriverWindowTest extends TestCase
{
    private $executor;

    protected function setUp(): void
    {
        $this->executor = $this->createMock(ExecuteMethod::class);
    }

    public function testGetPosition()
    {
        $this->executor->method('execute')
            ->with(DriverCommand::GET_WINDOW_POSITION, [':windowHandle' => 'current'])
            ->willReturn(['x' => 0, 'y' => 0]);

        $target = new WebDriverWindow($this->executor);
        $result = $target->getPosition();

        $this->assertInstanceOf(WebDriverPoint::class, $result);
        $this->assertEquals(0, $result->getX());
        $this->assertEquals(0, $result->getY());
    }

    public function w3cCompliantDataProvider()
    {
        yield [true];
        yield [false];
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testGetSize($w3cCompliant)
    {
        if ($w3cCompliant) {
            $result = ['width' => 100, 'height' => 100, 'x' => 0, 'y' => 0];

            $this->executor->method('execute')
                ->with(DriverCommand::GET_WINDOW_SIZE, [':windowHandle' => 'current'])
                ->willReturn($result);
        } else {
            $result = ['width' => 100, 'height' => 100];

            $this->executor->method('execute')
                ->withConsecutive(
                    [DriverCommand::GET_WINDOW_SIZE, [':windowHandle' => 'current']],
                    [DriverCommand::GET_WINDOW_POSITION, [':windowHandle' => 'current']]
                )
                ->willReturnOnConsecutiveCalls(
                    $result,
                    ['x' => 0, 'y' => 0]
                );
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);

        $result = $target->getSize();

        $this->assertInstanceOf(WebDriverDimension::class, $result);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
        $this->assertEquals(0, $result->getScreenX());
        $this->assertEquals(0, $result->getScreenY());
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testMinimize($w3cCompliant)
    {
        if ($w3cCompliant) {
            $result = ['width' => 100, 'height' => 100, 'x' => 0, 'y' => 0];

            $this->executor->method('execute')
                ->with(DriverCommand::MINIMIZE_WINDOW, [])
                ->willReturn($result);
        } else {
            $this->expectException(UnsupportedOperationException::class);
            $this->expectExceptionMessage('Minimize window is only supported in W3C mode');
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $result = $target->minimize();

        $this->assertInstanceOf(WebDriverDimension::class, $result);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
        $this->assertEquals(0, $result->getScreenX());
        $this->assertEquals(0, $result->getScreenY());
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testMaximize($w3cCompliant)
    {
        $result = ['width' => 100, 'height' => 100, 'x' => 0, 'y' => 0];

        if ($w3cCompliant) {
            $this->executor->method('execute')
                ->with(DriverCommand::MAXIMIZE_WINDOW, [])
                ->willReturn($result);
        } else {
            $this->executor->method('execute')
                ->with(DriverCommand::MAXIMIZE_WINDOW, [':windowHandle' => 'current'])
                ->willReturn($result);
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $result = $target->maximize();

        $this->assertInstanceOf(WebDriverDimension::class, $result);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
        $this->assertEquals(0, $result->getScreenX());
        $this->assertEquals(0, $result->getScreenY());
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testFullscreen($w3cCompliant)
    {
        if ($w3cCompliant) {
            $result = ['width' => 100, 'height' => 100, 'x' => 0, 'y' => 0];

            $this->executor->method('execute')
                ->with(DriverCommand::FULLSCREEN_WINDOW, [])
                ->willReturn($result);
        } else {
            $this->expectException(UnsupportedOperationException::class);
            $this->expectExceptionMessage('The Fullscreen window command is only supported in W3C mode');
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $result = $target->fullscreen();

        $this->assertInstanceOf(WebDriverDimension::class, $result);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
        $this->assertEquals(0, $result->getScreenX());
        $this->assertEquals(0, $result->getScreenY());
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testSetSize($w3cCompliant)
    {
        $dimension = new WebDriverDimension(
            100,
            100,
            0,
            0
        );

        if ($w3cCompliant) {
            $this->executor->method('execute')
                ->with(DriverCommand::SET_WINDOW_SIZE, ['width' => 100, 'height' => 100, ':windowHandle' => 'current'])
                ->willReturn(['width' => 100, 'height' => 100, 'x' => 0, 'y' => 0]);
        } else {
            $this->executor->method('execute')
                ->withConsecutive(
                    [DriverCommand::SET_WINDOW_SIZE, ['width' => 100, 'height' => 100, ':windowHandle' => 'current']],
                    [DriverCommand::GET_WINDOW_SIZE, [':windowHandle' => 'current']],
                    [DriverCommand::GET_WINDOW_POSITION, [':windowHandle' => 'current']]
                )
                ->willReturnOnConsecutiveCalls(
                    null,
                    ['width' => 100, 'height' => 100],
                    ['x' => 0, 'y' => 0]
                );
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $result = $target->setSize($dimension);

        $this->assertInstanceOf(WebDriverDimension::class, $result);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
        $this->assertEquals(0, $result->getScreenX());
        $this->assertEquals(0, $result->getScreenY());
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testSetPosition($w3cCompliant)
    {
        $point = new WebDriverPoint(
            0,
            0
        );

        if ($w3cCompliant) {
            $this->executor->method('execute')
                ->with(
                    DriverCommand::SET_WINDOW_POSITION,
                    ['x' => 0, 'y' => 0, ':windowHandle' => 'current']
                )
                ->willReturn(['width' => 100, 'height' => 100, 'x' => 0, 'y' => 0]);
        } else {
            $this->executor->method('execute')
                ->withConsecutive(
                    [DriverCommand::SET_WINDOW_POSITION, ['x' => 0, 'y' => 0, ':windowHandle' => 'current']],
                    [DriverCommand::GET_WINDOW_SIZE, [':windowHandle' => 'current']],
                    [DriverCommand::GET_WINDOW_POSITION, [':windowHandle' => 'current']]
                )
                ->willReturnOnConsecutiveCalls(
                    null,
                    ['width' => 100, 'height' => 100],
                    ['x' => 0, 'y' => 0]
                );
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $result = $target->setPosition($point);

        $this->assertInstanceOf(WebDriverDimension::class, $result);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
        $this->assertEquals(0, $result->getScreenX());
        $this->assertEquals(0, $result->getScreenY());
    }

    /**
     * @dataProvider w3cCompliantDataProvider
     * @param bool $w3cCompliant
     */
    public function testGetScreenOrientation($w3cCompliant)
    {
        if ($w3cCompliant) {
            $this->expectException(UnsupportedOperationException::class);
            $this->expectExceptionMessage('The Screen Orientation window command is only supported in OSS mode');
        } else {
            $this->executor
                ->method('execute')
                ->with(DriverCommand::GET_SCREEN_ORIENTATION)
                ->willReturn('LANDSCAPE');
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $result = $target->getScreenOrientation();

        if (method_exists($this, 'assertIsString')) {
            $this->assertIsString($result);
        } else {
            /** @phpstan-ignore-next-line */
            $this->assertInternalType('string', $result);
        }

        $this->assertEquals('LANDSCAPE', $result);
    }

    public function w3cCompliantDataProviderOrientation()
    {
        yield [true, 'LANDSCAPE'];
        yield [false, 'LANDSCAPE'];
        yield [false, 'FOO'];
    }

    /**
     * @dataProvider w3cCompliantDataProviderOrientation
     * @param bool $w3cCompliant
     * @param string $orientation
     */
    public function testSetScreenOrientation($w3cCompliant, $orientation)
    {
        if ($w3cCompliant) {
            $this->expectException(UnsupportedOperationException::class);
            $this->expectExceptionMessage('The Screen Orientation window command is only supported in OSS mode');
        } elseif ($orientation === 'FOO') {
            $this->expectException(IndexOutOfBoundsException::class);
            $this->expectExceptionMessage('Orientation must be either PORTRAIT, or LANDSCAPE');
        } else {
            $this->executor
                ->method('execute')
                ->with(DriverCommand::SET_SCREEN_ORIENTATION, ['orientation' => $orientation]);
        }

        $target = new WebDriverWindow($this->executor, $w3cCompliant);
        $target->setScreenOrientation($orientation);

        $this->assertTrue(true);
    }
}
