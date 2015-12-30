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

class DesiredCapabilitiesTest extends \PHPUnit_Framework_TestCase
{
  public function testShouldInstantiateWithCapabilitiesGivenInConstructor()
  {
    $capabilities = new DesiredCapabilities(
      array('fooKey' => 'fooVal', WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY)
    );

    $this->assertSame('fooVal', $capabilities->getCapability('fooKey'));
    $this->assertSame('ANY', $capabilities->getPlatform());

    $this->assertSame(
      array('fooKey' => 'fooVal', WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY),
      $capabilities->toArray()
    );
  }

  public function testShouldInstantiateEmptyInstance()
  {
    $capabilities = new DesiredCapabilities();

    $this->assertNull($capabilities->getCapability('foo'));
    $this->assertSame(array(), $capabilities->toArray());
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

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage isJavascriptEnable() is a htmlunit-only option
   */
  public function testShouldNotAllowToDisableJavascriptForNonHtmlUnitBrowser()
  {
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
  )
  {
    /** @var DesiredCapabilities $capabilities */
    $capabilities = call_user_func(array('Facebook\WebDriver\Remote\DesiredCapabilities', $setupMethod));

    $this->assertSame($expectedBrowser, $capabilities->getBrowserName());
    $this->assertSame($expectedPlatform, $capabilities->getPlatform());
  }

  /**
   * @return array
   */
  public function browserCapabilitiesProvider()
  {
    return array(
      array('android', WebDriverBrowserType::ANDROID, WebDriverPlatform::ANDROID),
      array('chrome', WebDriverBrowserType::CHROME, WebDriverPlatform::ANY),
      array('firefox', WebDriverBrowserType::FIREFOX, WebDriverPlatform::ANY),
      array('htmlUnit', WebDriverBrowserType::HTMLUNIT, WebDriverPlatform::ANY),
      array('htmlUnitWithJS', WebDriverBrowserType::HTMLUNIT, WebDriverPlatform::ANY),
      array('internetExplorer', WebDriverBrowserType::IE, WebDriverPlatform::WINDOWS),
      array('iphone', WebDriverBrowserType::IPHONE, WebDriverPlatform::MAC),
      array('ipad', WebDriverBrowserType::IPAD, WebDriverPlatform::MAC),
      array('opera', WebDriverBrowserType::OPERA, WebDriverPlatform::ANY),
      array('safari', WebDriverBrowserType::SAFARI, WebDriverPlatform::ANY),
      array('phantomjs', WebDriverBrowserType::PHANTOMJS, WebDriverPlatform::ANY),
    );
  }

  public function testShouldSetupFirefoxProfileAndDisableReaderViewForFirefoxBrowser()
  {
    $capabilities = DesiredCapabilities::firefox();

    /** @var FirefoxProfile $firefoxProfile */
    $firefoxProfile = $capabilities->getCapability(FirefoxDriver::PROFILE);
    $this->assertInstanceOf('Facebook\WebDriver\Firefox\FirefoxProfile', $firefoxProfile);

    $this->assertSame('false', $firefoxProfile->getPreference(FirefoxPreferences::READER_PARSE_ON_LOAD_ENABLED));
  }
}
