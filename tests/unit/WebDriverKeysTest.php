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

use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\WebDriverKeys
 */
class WebDriverKeysTest extends TestCase
{
    /**
     * @dataProvider provideKeys
     * @param mixed $keys
     * @param array $expectedOutput
     */
    public function testShouldEncodeKeysToArrayOfStrings($keys, $expectedOutput)
    {
        $this->assertSame($expectedOutput, WebDriverKeys::encode($keys));
    }

    /**
     * @return array[]
     */
    public function provideKeys()
    {
        return [
            'empty string' => ['', ['']],
            'simple string' => ['foo', ['foo']],
            'string as an array' => [['foo'], ['foo']],
            'string with modifier as an array' => [[WebDriverKeys::SHIFT, 'foo'], [WebDriverKeys::SHIFT, 'foo']],
            'string with concatenated modifier' => [[WebDriverKeys::SHIFT . 'foo'], [WebDriverKeys::SHIFT . 'foo']],
            'simple numeric value' => [3, ['3']],
            'multiple numeric values' => [[1, 3.33], ['1', '3.33']],
            'multiple mixed values ' => [['foo', WebDriverKeys::END, '1.234'], ['foo', WebDriverKeys::END, '1.234']],
            'array of strings with modifiers should separate them with NULL character' => [
                [[WebDriverKeys::SHIFT, 'foo'], [WebDriverKeys::META, 'bar']],
                [WebDriverKeys::SHIFT . 'foo' . WebDriverKeys::NULL, WebDriverKeys::META . 'bar' . WebDriverKeys::NULL],
            ],
            'null' => [null, []],
        ];
    }
}
