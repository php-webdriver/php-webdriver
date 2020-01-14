<?php

namespace Facebook\WebDriver\Interactions\Internal;

use PHPUnit\Framework\TestCase;

class WebDriverCoordinatesTest extends TestCase
{
    public function testConstruct()
    {
        $in_view_port = function () {
        };
        $on_page = function () {
        };

        $webDriverCoordinates = new WebDriverCoordinates(null, $in_view_port, $on_page, 'auxiliary');

        $this->assertAttributeEquals(null, 'onScreen', $webDriverCoordinates);
        $this->assertAttributeEquals($in_view_port, 'inViewPort', $webDriverCoordinates);
        $this->assertAttributeEquals($on_page, 'onPage', $webDriverCoordinates);
        $this->assertAttributeEquals('auxiliary', 'auxiliary', $webDriverCoordinates);
    }

    public function testGetAuxiliary()
    {
        $in_view_port = function () {
        };
        $on_page = function () {
        };

        $webDriverCoordinates = new WebDriverCoordinates(null, $in_view_port, $on_page, 'auxiliary');

        $this->assertEquals('auxiliary', $webDriverCoordinates->getAuxiliary());
    }
}
