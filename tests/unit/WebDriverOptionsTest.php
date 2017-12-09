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

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecuteMethod;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\WebDriverOptions
 */
class WebDriverOptionsTest extends TestCase
{
    /** @var ExecuteMethod|\PHPUnit_Framework_MockObject_MockObject */
    private $executor;

    public function setUp()
    {
        $this->executor = $this->getMockBuilder(ExecuteMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldAddCookieFromArray()
    {
        $cookieInArray = [
            'name' => 'cookieName',
            'value' => 'someValue',
            'path' => '/bar',
            'domain' => 'foo',
            'expiry' => 1485388333,
            'secure' => false,
            'httpOnly' => false,
        ];

        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::ADD_COOKIE, ['cookie' => $cookieInArray]);

        $options = new WebDriverOptions($this->executor);

        $options->addCookie($cookieInArray);
    }

    public function testShouldAddCookieFromCookieObject()
    {
        $cookieObject = new Cookie('cookieName', 'someValue');
        $cookieObject->setPath('/bar');
        $cookieObject->setDomain('foo');
        $cookieObject->setExpiry(1485388333);
        $cookieObject->setSecure(false);
        $cookieObject->setHttpOnly(false);

        $expectedCookieData = [
            'name' => 'cookieName',
            'value' => 'someValue',
            'path' => '/bar',
            'domain' => 'foo',
            'expiry' => 1485388333,
            'secure' => false,
            'httpOnly' => false,
        ];

        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::ADD_COOKIE, ['cookie' => $expectedCookieData]);

        $options = new WebDriverOptions($this->executor);

        $options->addCookie($cookieObject);
    }

    public function testShouldNotAllowToCreateCookieFromDifferentObjectThanCookie()
    {
        $notCookie = new \stdClass();

        $options = new WebDriverOptions($this->executor);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cookie must be set from instance of Cookie class or from array.');
        $options->addCookie($notCookie);
    }

    public function testShouldGetAllCookies()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::GET_ALL_COOKIES)
            ->willReturn(
                [
                    [
                        'path' => '/',
                        'domain' => '*.seleniumhq.org',
                        'name' => 'firstCookie',
                        'httpOnly' => false,
                        'secure' => true,
                        'value' => 'value',
                    ],
                    [
                        'path' => '/',
                        'domain' => 'docs.seleniumhq.org',
                        'name' => 'secondCookie',
                        'httpOnly' => false,
                        'secure' => false,
                        'value' => 'foo',
                    ],
                ]
            );

        $options = new WebDriverOptions($this->executor);

        $cookies = $options->getCookies();

        $this->assertCount(2, $cookies);
        $this->assertContainsOnlyInstancesOf(Cookie::class, $cookies);
        $this->assertSame('firstCookie', $cookies[0]->getName());
        $this->assertSame('secondCookie', $cookies[1]->getName());
    }

    public function testShouldGetCookieByName()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::GET_ALL_COOKIES)
            ->willReturn(
                [
                    [
                        'path' => '/',
                        'domain' => '*.seleniumhq.org',
                        'name' => 'cookieToFind',
                        'httpOnly' => false,
                        'secure' => true,
                        'value' => 'value',
                    ],
                    [
                        'path' => '/',
                        'domain' => 'docs.seleniumhq.org',
                        'name' => 'otherCookie',
                        'httpOnly' => false,
                        'secure' => false,
                        'value' => 'foo',
                    ],
                ]
            );

        $options = new WebDriverOptions($this->executor);

        $cookie = $options->getCookieNamed('cookieToFind');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertSame('cookieToFind', $cookie->getName());
        $this->assertSame('value', $cookie->getValue());
        $this->assertSame('/', $cookie->getPath());
        $this->assertSame('*.seleniumhq.org', $cookie->getDomain());
        $this->assertFalse($cookie->isHttpOnly());
        $this->assertTrue($cookie->isSecure());
    }

    public function testShouldReturnNullIfCookieWithNameNotFound()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::GET_ALL_COOKIES)
            ->willReturn(
                [
                    [
                        'path' => '/',
                        'domain' => '*.seleniumhq.org',
                        'name' => 'cookieToNotFind',
                        'httpOnly' => false,
                        'secure' => true,
                        'value' => 'value',
                    ],
                ]
            );

        $options = new WebDriverOptions($this->executor);

        $this->assertNull($options->getCookieNamed('notExistingCookie'));
    }

    public function testShouldReturnTimeoutsInstance()
    {
        $options = new WebDriverOptions($this->executor);

        $timeouts = $options->timeouts();
        $this->assertInstanceOf(WebDriverTimeouts::class, $timeouts);
    }

    public function testShouldReturnWindowInstance()
    {
        $options = new WebDriverOptions($this->executor);

        $window = $options->window();
        $this->assertInstanceOf(WebDriverWindow::class, $window);
    }
}
