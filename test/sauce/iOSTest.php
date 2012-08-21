<?php
// Copyright 2012-present Element 34
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

require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriverWait.php');

class iOSTest extends PHPUnit_Framework_TestCase {
    protected static $driver;

    public function setUp() {
        $username = "";
        $key = "";
        $command_executor = "http://" . $username . ":" . $key . "@ondemand.saucelabs.com:80/wd/hub";
        self::$driver = new PHPWebDriver_WebDriver($command_executor);
    }

    public function tearDown() {
        $this->session->close();
    }

    /**
    * @group sauce
    * @group iphone43
    */
    public function testiPhone43() {
        $caps = array();
        $caps["platform"] = 'MAC';
        $caps["version"] = '4.3';
        $this->session = self::$driver->session("iphone", $caps);
        $this->session->open("https://saucelabs.com/login");
        $e = $this->session->element("id", "username");
        $e->sendKeys("gobblygook");
        $e = $this->session->element("id", "password");
        $e->sendKeys("nonsense");
        $e = $this->session->element("id", "submit");
        $e->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $e = $w->until(
                function($session) {
                  return $session->element("css selector", "error");
                }
             );
        $this->assertEquals($e->text, "Incorrect username or password.");
    }

    /**
    * @group sauce
    * @group iphone50
    */
    public function testiPhone50() {
        $caps = array();
        $caps["platform"] = 'MAC';
        $caps["version"] = '5.0';
        $this->session = self::$driver->session("iphone", $caps);
        $this->session->open("https://saucelabs.com/login");
        $e = $this->session->element("id", "username");
        $e->sendKeys("gobblygook");
        $e = $this->session->element("id", "password");
        $e->sendKeys("nonsense");
        $e = $this->session->element("id", "submit");
        $e->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $e = $w->until(
                function($session) {
                  return $session->element("css selector", "error");
                }
             );
        $this->assertEquals($e->text, "Incorrect username or password.");
    }

    /**
    * @group sauce
    * @group ipad43
    */
    public function testiPad43() {
        $caps = array();
        $caps["platform"] = 'MAC';
        $caps["version"] = '4.3';
        $this->session = self::$driver->session("ipad", $caps);
        $this->session->open("https://saucelabs.com/login");
        $e = $this->session->element("id", "username");
        $e->sendKeys("gobblygook");
        $e = $this->session->element("id", "password");
        $e->sendKeys("nonsense");
        $e = $this->session->element("id", "submit");
        $e->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $e = $w->until(
                function($session) {
                  return $session->element("css selector", "error");
                }
             );
        $this->assertEquals($e->text, "Incorrect username or password.");
    }

    /**
    * @group sauce
    * @group ipad50
    */
    public function testiPad50() {
        $caps = array();
        $caps["platform"] = 'MAC';
        $caps["version"] = '5.0';
        $this->session = self::$driver->session("ipad", $caps);
        $this->session->open("https://saucelabs.com/login");
        $e = $this->session->element("id", "username");
        $e->sendKeys("gobblygook");
        $e = $this->session->element("id", "password");
        $e->sendKeys("nonsense");
        $e = $this->session->element("id", "submit");
        $e->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $e = $w->until(
                function($session) {
                  return $session->element("css selector", "error");
                }
             );
        $this->assertEquals($e->text, "Incorrect username or password.");
    }

}