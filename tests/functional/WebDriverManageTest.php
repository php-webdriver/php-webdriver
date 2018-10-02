<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @coversDefaultClass \Facebook\WebDriver\WebDriverWindow
 */
class WebDriverManageTest extends WebDriverTestCase
{
    /**
     * @group exclude-travis
     * @covers ::maximize
     */
    public function testShouldMaximizeWindow()
    {
        if ($this->driver->getCapabilities()->is('moz:headless')) {
            $this->markTestSkipped('Cannot be executed in headless mode.');
        }
        $this->driver->get($this->getTestPageUrl('index.html'));
        $this->driver->manage()->window()->maximize();
    }

    /**
     * @group exclude-travis
     * @covers ::minimize
     */
    public function testShouldMinimizeWindow()
    {
        if (false === $this->driver->getDialect()->isW3C()) {
            $this->markTestSkipped('Minimize window command is not supported in OSS protocol.');
        }
        if ($this->driver->getCapabilities()->is('moz:headless')) {
            $this->markTestSkipped('Cannot be executed in headless mode.');
        }
        $this->driver->get($this->getTestPageUrl('index.html'));
        $this->driver->manage()->window()->minimize();
    }

    /**
     * @group exclude-travis
     * @covers ::fullscreen
     */
    public function testShouldFullscreenWindow()
    {
        if (false === $this->driver->getDialect()->isW3C()) {
            $this->markTestSkipped('Minimize window command is not supported in OSS protocol.');
        }
        if ($this->driver->getCapabilities()->is('moz:headless')) {
            $this->markTestSkipped('Cannot be executed in headless mode.');
        }
        $this->driver->get($this->getTestPageUrl('index.html'));
        $this->driver->manage()->window()->fullscreen();
    }

    /**
     * @covers ::setSize
     * @covers ::getSize
     * @covers ::getScreenOrientation
     * @covers ::setScreenOrientation
     * @covers ::setPosition
     * @covers ::getPosition
     */
    public function testShouldSetSizeAndGetPositionWindow()
    {
        if (WebDriverBrowserType::HTMLUNIT === $this->driver->getCapabilities()->getBrowserName()) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }
        if (WebDriverBrowserType::CHROME === $this->driver->getCapabilities()->getBrowserName()) {
            $this->markTestSkipped('Chrome does not support screen orientation commands.');
        }

        $this->driver->get($this->getTestPageUrl('index.html'));
        $window = $this->driver->manage()->window();

        $window->setSize(new WebDriverDimension(500, 600));
        $size = $window->getSize();
        $this->assertEquals(500, $size->getWidth());
        $this->assertEquals(600, $size->getHeight());

        $this->assertEquals('PORTRAIT', $window->getScreenOrientation());
        $window->setScreenOrientation('LANDSCAPE');
        $this->assertEquals('LANDSCAPE', $window->getScreenOrientation());

        $window->setPosition(new WebDriverPoint(20, 40));
        $point = $window->getPosition();
        $this->assertEquals(20, $point->getX());
        $this->assertEquals(40, $point->getY());
    }

    /**
     * @covers \Facebook\WebDriver\WebDriverOptions::addCookie
     * @covers \Facebook\WebDriver\WebDriverOptions::getCookies
     * @covers \Facebook\WebDriver\WebDriverOptions::getCookieNamed
     * @covers \Facebook\WebDriver\WebDriverOptions::deleteCookieNamed
     * @covers \Facebook\WebDriver\WebDriverOptions::deleteAllCookies
     */
    public function testShouldSetReadAndDeleteCookie()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $manage = $this->driver->manage();

        $cookieName = \uniqid('cookie_');
        $manage->addCookie(new Cookie($cookieName, '1'));
        $manage->addCookie(new Cookie('another_cookie', '2'));

        $this->assertCount(1, array_filter(
            $manage->getCookies(),
            function (Cookie $cookie) use ($cookieName) {
                return $cookieName === $cookie->getName();
            }
        ));

        $this->assertEquals(1, $manage->getCookieNamed($cookieName)->getValue());
        $manage->deleteCookieNamed($cookieName);
        $this->assertCount(1, $manage->getCookies());

        $manage->deleteAllCookies();
        $this->assertCount(0, $manage->getCookies());
    }
}
