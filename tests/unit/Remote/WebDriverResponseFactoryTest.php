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

use Facebook\WebDriver\Exception\WebDriverException;
use PHPUnit\Framework\TestCase;

class WebDriverResponseFactoryTest extends TestCase
{
    protected function setUp()
    {
        $this->markTestSkipped('Skip it until tests will be done.');
    }
    
    
    public function testCreateResponseJsonWireProtocol()
    {
        $results = [
            'status' => $status = 0,
            'sessionId' => $sessionId = 'ssid-dd344-dds2-445533sdd-sss',
            'value' => $value = [
                'data' => 1
            ]
        ];
        
        $response = WebDriverResponseFactory::create($results);
        $this->assertEquals($sessionId, $response->getSessionID());
        $this->assertEquals($status, $response->getStatus());
        $this->assertEquals($value, $response->getValue());
    }
    
    public function testCreateNewSessionGridInW3CProtocol()
    {
        $results = [
            'status' => $status = 0,
            'sessionId' => $sessionId = 'ssid-dd344-dds2-445533sdd-sss',
            'value' => $value = [
                'browseName' => 'firefox',
                'moz:profile' => 'var/folders/df/rty/73839',
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
                    'data' => 1
                ]
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
                'data' => 1
            ]
        ];
        
        $response = WebDriverResponseFactory::create($results);
        $this->assertNull($response->getSessionID());
        $this->assertEquals(0, $response->getStatus());
        $this->assertEquals($value, $response->getValue());
    }
    
    public function testShouldThrowWebDriverExceptionForFailedRequestInW3CProtocol()
    {
        $this->expectException(WebDriverException::class);
        
        $results = [
            'value' => [
                'error' => 'invalid session id',
                'message' => 'No active session with ID 1234',
                'stacktrace' => ''
            ]
        ];
        
        WebDriverResponseFactory::create($results);
    }
}
