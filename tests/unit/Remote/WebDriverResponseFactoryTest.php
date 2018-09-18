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

use Facebook\WebDriver\Exception\ElementNotSelectableException;
use Facebook\WebDriver\Exception\InvalidCookieDomainException;
use Facebook\WebDriver\Exception\InvalidElementStateException;
use Facebook\WebDriver\Exception\InvalidSelectorException;
use Facebook\WebDriver\Exception\MoveTargetOutOfBoundsException;
use Facebook\WebDriver\Exception\NoAlertOpenException;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\NoSuchWindowException;
use Facebook\WebDriver\Exception\ScriptTimeoutException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnableToSetCookieException;
use Facebook\WebDriver\Exception\UnknownCommandException;
use Facebook\WebDriver\Exception\UnknownServerException;
use Facebook\WebDriver\Exception\WebDriverException;
use PHPUnit\Framework\TestCase;

class WebDriverResponseFactoryTest extends TestCase
{
    public function testCreateResponseJsonWireProtocol()
    {
        $results = [
            'status' => $status = 0,
            'sessionId' => $sessionId = 'ssid-dd344-dds2-445533sdd-sss',
            'value' => $value = [
                'data' => 1,
            ]
        ];

        $response = WebDriverResponseFactory::create($results);
        $this->assertEquals($sessionId, $response->getSessionID());
        $this->assertEquals($status, $response->getStatus());
        $this->assertEquals($value, $response->getValue());
    }

    public function testCreateNewSessionInW3CProtocol()
    {
        $results = [
            'value' => [
                'sessionId' => $sessionId = 'ssid-dd344-dds2-445533sdd-sss',
                'capabilities' => $value = [
                    'data' => 1,
                ],
            ]
        ];

        $response = WebDriverResponseFactory::create($results);
        $this->assertEquals($sessionId, $response->getSessionID());
        $this->assertEquals(0, $response->getStatus());
        $this->assertEquals($value, $response->getValue());
    }

    public function testCreateResponseInW3CProtocol()
    {
        $results = [
            'value' => $value = [
                'data' => 1,
            ],
        ];

        $response = WebDriverResponseFactory::create($results);
        $this->assertNull($response->getSessionID());
        $this->assertEquals(0, $response->getStatus());
        $this->assertEquals($value, $response->getValue());
    }

    /**
     * @dataProvider getW3CDataProvider
     * @param string $error
     * @param string $expectedException
     * @throws WebDriverException
     */
    public function testShouldThrowExceptionW3C($error, $expectedException)
    {
        $this->expectException($expectedException);

        $dialect = WebDriverDialect::createW3C();
        $result = [
            'value' => [
                'error' => $error
            ]
        ];
        WebDriverResponseFactory::checkExecutorResult($dialect, $result);
    }

    /**
     * @return array
     */
    public function getW3CDataProvider()
    {
        return [
            ['element click intercepted', InvalidElementStateException::class],
            ['invalid element state', InvalidElementStateException::class],
            ['element not interactable', ElementNotSelectableException::class],
            ['no such element', NoSuchElementException::class],
            ['timeout', TimeOutException::class],
            ['script timeout', ScriptTimeoutException::class],
            ['no such window', NoSuchWindowException::class],
            ['invalid cookie domain', InvalidCookieDomainException::class],
            ['unable to set cookie', UnableToSetCookieException::class],
            ['unknown command', UnknownCommandException::class],
            ['unknown error', UnknownServerException::class],
            ['invalid selector', InvalidSelectorException::class],
            ['move target out of bounds', MoveTargetOutOfBoundsException::class],
            ['stale element reference', StaleElementReferenceException::class],
            ['no such alert', NoAlertOpenException::class],
            ['invalid session id', WebDriverException::class],
        ];
    }
}
