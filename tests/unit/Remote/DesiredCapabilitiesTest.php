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

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxPreferences;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\WebDriverPlatform;
use PHPUnit\Framework\TestCase;

class DesiredCapabilitiesTest extends TestCase
{
    public function testShouldInstantiateWithCapabilitiesGivenInConstructor()
    {
        $capabilities = new DesiredCapabilities(
            ['fooKey' => 'fooVal', WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY]
        );

        $this->assertSame('fooVal', $capabilities->getCapability('fooKey'));
        $this->assertSame('ANY', $capabilities->getPlatform());

        $this->assertSame(
            ['fooKey' => 'fooVal', WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY],
            $capabilities->toArray()
        );
    }

    public function testShouldInstantiateEmptyInstance()
    {
        $capabilities = new DesiredCapabilities();

        $this->assertNull($capabilities->getCapability('foo'));
        $this->assertSame([], $capabilities->toArray());
    }

    public function testShouldProvideAccessToCapabilitiesUsingSettersAndGetters()
    {
        $capabilities = new DesiredCapabilities();
        // generic capability setter
        $capabilities->setCapability('custom', 1337);
        // specific setters
        $capabilities->setBrowserName(WebDriverBrowserType::CHROME);
        $capabilities->setPlatform(WebDriverPlatform::LINUX);
        $capabilities->setVersion(333);

        $this->assertSame(1337, $capabilities->getCapability('custom'));
        $this->assertSame(WebDriverBrowserType::CHROME, $capabilities->getBrowserName());
        $this->assertSame(WebDriverPlatform::LINUX, $capabilities->getPlatform());
        $this->assertSame(333, $capabilities->getVersion());
    }

    public function testShouldNotAllowToDisableJavascriptForNonHtmlUnitBrowser()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('isJavascriptEnabled() is a htmlunit-only option');

        $capabilities = new DesiredCapabilities();
        $capabilities->setBrowserName(WebDriverBrowserType::FIREFOX);
        $capabilities->setJavascriptEnabled(false);
    }

    public function testShouldAllowToDisableJavascriptForHtmlUnitBrowser()
    {
        $capabilities = new DesiredCapabilities();
        $capabilities->setBrowserName(WebDriverBrowserType::HTMLUNIT);
        $capabilities->setJavascriptEnabled(false);

        $this->assertFalse($capabilities->isJavascriptEnabled());
    }

    /**
     * @dataProvider browserCapabilitiesProvider
     * @param string $setupMethod
     * @param string $expectedBrowser
     * @param string $expectedPlatform
     */
    public function testShouldProvideShortcutSetupForCapabilitiesOfEachBrowser(
        $setupMethod,
        $expectedBrowser,
        $expectedPlatform
    ) {
        /** @var DesiredCapabilities $capabilities */
        $capabilities = call_user_func([DesiredCapabilities::class, $setupMethod]);

        $this->assertSame($expectedBrowser, $capabilities->getBrowserName());
        $this->assertSame($expectedPlatform, $capabilities->getPlatform());
    }

    /**
     * @return array
     */
    public function browserCapabilitiesProvider()
    {
        return [
            ['android', WebDriverBrowserType::ANDROID, WebDriverPlatform::ANDROID],
            ['chrome', WebDriverBrowserType::CHROME, WebDriverPlatform::ANY],
            ['firefox', WebDriverBrowserType::FIREFOX, WebDriverPlatform::ANY],
            ['htmlUnit', WebDriverBrowserType::HTMLUNIT, WebDriverPlatform::ANY],
            ['htmlUnitWithJS', WebDriverBrowserType::HTMLUNIT, WebDriverPlatform::ANY],
            ['MicrosoftEdge', WebDriverBrowserType::MICROSOFT_EDGE, WebDriverPlatform::WINDOWS],
            ['internetExplorer', WebDriverBrowserType::IE, WebDriverPlatform::WINDOWS],
            ['iphone', WebDriverBrowserType::IPHONE, WebDriverPlatform::MAC],
            ['ipad', WebDriverBrowserType::IPAD, WebDriverPlatform::MAC],
            ['opera', WebDriverBrowserType::OPERA, WebDriverPlatform::ANY],
            ['safari', WebDriverBrowserType::SAFARI, WebDriverPlatform::ANY],
            ['phantomjs', WebDriverBrowserType::PHANTOMJS, WebDriverPlatform::ANY],
        ];
    }

    public function testShouldSetupFirefoxProfileAndDisableReaderViewForFirefoxBrowser()
    {
        $capabilities = DesiredCapabilities::firefox();

        /** @var FirefoxProfile $firefoxProfile */
        $firefoxProfile = $capabilities->getCapability(FirefoxDriver::PROFILE);
        $this->assertInstanceOf(FirefoxProfile::class, $firefoxProfile);

        $this->assertSame('false', $firefoxProfile->getPreference(FirefoxPreferences::READER_PARSE_ON_LOAD_ENABLED));
    }
}
