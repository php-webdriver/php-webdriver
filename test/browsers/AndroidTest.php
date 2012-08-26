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

namespace WebDriver;

require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriverWait.php');
require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriverSession.php');
require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriverTouchActions.php');
require_once(dirname(__FILE__) . '/../sauce/pages/login.php');


class AndroidTest extends \PHPUnit_Framework_TestCase {
    protected static $driver;

    public function setUp() {
        $command_executor = "http://localhost:8080/wd/hub";
        self::$driver = new \PHPWebDriver_WebDriver($command_executor);
    }

    public function tearDown() {
        $this->session->close();
    }

    /**
    * @group android
    * @group click
    */
    public function testAndroidClick() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/clicks.html");
        $e = $this->session->element("id", "normal");
        $this->session->touch()->click(array('element' => $e->getID()));
        $w = new \PHPWebDriver_WebDriverWait($this->session, 5);
        $e = $w->until(
          function($session) {
            return $session->element("css selector", "h1.header");
          }
        );
        assert($e->text() == "XHTML Might Be The Future");
    }

    /**
    * @group android
    * @group down
    * @group up
    * @group move
    */
    public function testAndroidDownMoveUp() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/longContentPage.html");
        $e = $this->session->element("id", "imagestart");
        $top = $e->location();
        $this->session->touch()->down(array('x' => $top['x'], 'y' => $top['y']));

        $e = $this->session->element("id", "link4");        
        $bottom = $e->location();
        $this->session->touch()->move(array('x' => $bottom['x'], 'y' => $bottom['y']));
        $this->session->touch()->up(array('x' => $bottom['x'], 'y' => $bottom['y']));
    }

    /**
    * @group android
    * @group scroll
    */
    public function testAndroidElementScroll() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/longContentPage.html");
        $e = $this->session->element("id", "imagestart");
        $this->session->touch()->scroll(array('element' => $e->getID(), 'xoffset' => 100, 'yoffset' => 0));
    }

    /**
    * @group android
    * @group scroll
    */
    public function testAndroidScroll() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/longContentPage.html");

        $e = $this->session->element("id", "imagestart");
        $top = $e->location();

        $e = $this->session->element("id", "link4");        
        $bottom = $e->location();

        $this->session->touch()->scroll(array('xoffset' => $top['x'] + $bottom['x'], 'yoffset' => 0));
    }

    /**
    * @group android
    * @group doubleclick
    */
    public function testAndroidDoubleClick() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/longContentPage.html");

        $e = $this->session->element("id", "imagestart");

        $this->session->touch()->doubleclick(array('element' => $e->getID()));
        
        $after = $e->location();
    }

    /**
    * @group android
    * @group longclick
    */
    public function testAndroidLongClick() {
        $this->markTestSkipped('The Java test for this uses mocks; anyone have a real page for it?');
        // $this->session->touch()->longclick(array('element' => $e->getID()));
    }

    /**
    * @group android
    * @group flick
    */
    public function testAndroidElementFlick() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/longContentPage.html");

        $to_flick = $this->session->element("id", "imagestart");
        $link = $this->session->element("id", "link1");

        $before = $link->location();
        $this->assertTrue($before['x'] > 1500);
        
        $this->session->touch()->flick(array('element' => $to_flick->getID(), 'xoffset' => -1000, 'yoffset' => 0, 'speed' => \PHPWebDriver_WebDriverSession::FLICK_SPEED_NORMAL));

        $after = $link->location();
        $this->assertTrue($after['x'] < 1500);
    }

    /**
    * @group android
    * @group flick
    * @group wtfisxspeed
    */
    public function testAndroidFlick() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/longContentPage.html");

        $to_flick = $this->session->element("id", "imagestart");
        $link = $this->session->element("id", "link1");

        $before = $link->location();
        $this->assertTrue($before['x'] > 1500);
        
        $this->session->touch()->flick(array('xspeed' => -1000, 'yspeed' => 0));

        $after = $link->location();
        $this->assertTrue($after['x'] < 1500);
    }

    /**
    * @group android
    * @group chains
    */
    public function testAndroidChains() {
        $this->session = self::$driver->session("android");
        $this->session->open("http://www.illicitonion.com/selenium-web/clicks.html");
        
        $e = $this->session->element("id", "normal");
        $a = new \PHPWebDriver_WebDriverTouchActions($this->session);
        $a = $a->single_tap($e);
        $a = $a->down(1000, 50);
        $a = $a->move(-1000, 0);
        $a = $a->up(-2000, 50);
        $a = $a->element_scroll($e, -2000, 50);
        $a = $a->scroll(-2000, 50);
        $a = $a->double_tap($e);
        $a = $a->long_tap($e);
        $a = $a->flick(40, 400);
        $a = $a->element_flick($e, 40, 400, \PHPWebDriver_WebDriverSession::FLICK_SPEED_NORMAL);
        $a->perform();
    }

}