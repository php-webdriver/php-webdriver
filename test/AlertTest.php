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
require_once('LocalWebServer.php');

class AlertTest extends PHPUnit_Framework_TestCase {
    protected static $driver;
    protected static $pid;
    protected static $port;

    public static function setUpBeforeClass() {
        if (function_exists('pcntl_fork') && function_exists('posix_kill')) {
            // get an empty socket
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
            socket_bind($socket, '127.0.0.1', 0);
            socket_getsockname($socket, $socket_address, $port);
            socket_close($socket);
            // set it somewhere
            self::$port = $port;
            // fork the webserver
            self::$pid = pcntl_fork();
            if (self::$pid == -1) {
                return  $this->raiseError('Could not fork child process.');
            } elseif (self::$pid == 0) {
                $webserver = &new LocalWebServer('127.0.0.1', $port);
                $webserver->_driver->setDebugMode(false);
                $webserver->documentRoot = dirname(__FILE__) . '/../www';
                $webserver->start();
            } else {
                // the parent process does not have to do anything
            }
        }
    }

    public function setUp() {
        self::$driver = new PHPWebDriver_WebDriver();
    }

    public function tearDown() {
        $this->session->close();
    }

    public static function tearDownAfterClass() {
        // kill the server
        posix_kill(self::$pid, SIGKILL);
    }
    
    /**
    * @group alert
    */
    public function testAlertText() {
        $this->session = self::$driver->session();
        $this->session->open("http://127.0.0.1:" . self::$port . "/alerts.html");
        $e = $this->session->element("id", "alert");
        $e->click();
        $a = $this->session->switch_to_alert();
        $this->assertEquals($a->text, "cheese");
    }

    /**
    * @group alert
    * @group tuesday
    */
    public function testAlertDismiss() {
        $this->session = self::$driver->session();
        $this->session->open("http://127.0.0.1:" . self::$port . "/alerts.html");
        $e = $this->session->element("id", "alert");
        $e->click();
        $a = $this->session->switch_to_alert();
        $a->accept();
        
        // If we can perform any action, we're good to go
        assert("Testing Alerts" == $this->session->title());
    }

    /**
    * @group alert
    */
    public function testAlertAcept() {
        $this->session = self::$driver->session();
        $this->session->open("http://127.0.0.1:" . self::$port . "/alerts.html");
        $e = $this->session->element("id", "alert");
        $e->click();
        $a = $this->session->switch_to_alert();
        $a->accept();
        
        // If we can perform any action, we're good to go
        assert("Testing Alerts" == $this->session->title());
    }

    /**
    * @group alert
    */
    public function testAlertSendKeys() {
        $this->session = self::$driver->session();
        $this->session->open("https://github.com/element-34/php-webdriver");
    }
}
