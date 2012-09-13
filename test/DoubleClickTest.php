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

require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverBy.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverActionChains.php');

class DoubleClickTest extends PHPUnit_Framework_TestCase {
    protected static $driver;
    protected static $pid;
    protected static $port;

    public function setUp() {
        self::$driver = new PHPWebDriver_WebDriver();
    }

    public function tearDown() {
        $this->session->close();
    }

    
    /**
    * @group doubleclick
    */
    public function testDoubleClick() {
        $this->session = self::$driver->session();
        $this->session->open("http://api.jquery.com/dblclick/");
        
        // switch to our frame
        $iframe = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "iframe");
        $this->session->moveto(array("element" => $iframe->getID()));
        $this->session->switch_to_frame($iframe);
                
        // masure sure that things are in the state we want
        $e = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div");
        $clazz = $e->attribute('class');
        $this->assertEquals(null, $clazz);

        $ac = new PHPWebDriver_WebDriverActionChains($this->session);
        $ac->doubleClick($this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div"));
        $ac->perform();

        $e = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div");
        $clazz = $e->attribute('class');
        $this->assertEquals('dbl', $clazz);
        
        $this->session->switch_to_frame();

    }

}
