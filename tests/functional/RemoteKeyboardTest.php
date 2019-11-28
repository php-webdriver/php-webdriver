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
use Facebook\WebDriver\WebDriverKeys;

/**
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteKeyboard
 */
class RemoteKeyboardTest extends WebDriverTestCase
{
    /**
     * @covers ::getTitle
     */
    public function testShouldGetPageTitle()
    {
        //self::skipForJsonWireProtocol();

        $this->driver->get($this->getTestPageUrl('form.html'));

        $input = $this->driver->findElement(WebDriverBy::id('input-text'));
        $input->click();

        $this->driver->execute(DriverCommand::ACTIONS, [
            'actions' => [
                [
                    'type' => 'key',
                    'id' => 'keyboard',
                    'actions' => [
                        ['type' => 'keyDown', 'value' => WebDriverKeys::COMMAND],
                        ['type' => 'keyDown', 'value' => 'a'],
                        ['type' => 'keyUp', 'value' => 'a'],
                        ['type' => 'keyUp', 'value' => WebDriverKeys::COMMAND],
                        ['type' => 'keyDown', 'value' => WebDriverKeys::BACKSPACE],
                        ['type' => 'keyUp', 'value' => WebDriverKeys::BACKSPACE],
                    ],
                ],
            ],
        ]);

        $this->assertEquals(
            '',
            $input->getAttribute('value')
        );
    }
}
