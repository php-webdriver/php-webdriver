<?php declare(strict_types=1);

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\WebDriverPoint;
use PHPUnit\Framework\TestCase;

class WebDriverCoordinatesTest extends TestCase
{
    public function testConstruct(): void
    {
        $in_view_port = function () {
            return new WebDriverPoint(0, 0);
        };
        $on_page = function () {
            return new WebDriverPoint(10, 10);
        };

        $webDriverCoordinates = new WebDriverCoordinates(null, $in_view_port, $on_page, 'auxiliary');

        $this->assertEquals(new WebDriverPoint(0, 0), $webDriverCoordinates->inViewPort());
        $this->assertEquals(new WebDriverPoint(10, 10), $webDriverCoordinates->onPage());
        $this->assertSame('auxiliary', $webDriverCoordinates->getAuxiliary());
    }
}
