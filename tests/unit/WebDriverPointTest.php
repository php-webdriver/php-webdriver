<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\WebDriverPoint
 */
class WebDriverPointTest extends TestCase
{
    public function testEqualsUsesPublicIntegerCoordinates(): void
    {
        $fractional = new WebDriverPoint(0.75, -2.75);
        $integer = new WebDriverPoint(0, -2);

        $this->assertSame(0, $fractional->getX());
        $this->assertSame(-2, $fractional->getY());
        $this->assertTrue($fractional->equals($fractional));
        $this->assertTrue($fractional->equals($integer));
        $this->assertTrue($integer->equals($fractional));
        $this->assertFalse($fractional->equals(new WebDriverPoint(1, -2)));
        $this->assertFalse($fractional->equals(new WebDriverPoint(0, -3)));
    }

    public function testEqualsAfterMovingFloatCoordinates(): void
    {
        $point = new WebDriverPoint(0, 0);
        $point->move(1.5, -1.5);

        $this->assertTrue($point->equals(new WebDriverPoint(1, -1)));

        $point->moveBy(0.75, -0.75);

        $this->assertSame(2, $point->getX());
        $this->assertSame(-2, $point->getY());
        $this->assertTrue($point->equals(new WebDriverPoint(2, -2)));
        $this->assertFalse($point->equals(new WebDriverPoint(1, -1)));
    }
}
