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

namespace Facebook\WebDriver\Support;

use PHPUnit\Framework\TestCase;

class XPathEscaperTest extends TestCase
{
    /**
     * @dataProvider xpathProvider
     * @param string $input
     * @param string $expectedOutput
     */
    public function testShouldInstantiateWithCapabilitiesGivenInConstructor($input, $expectedOutput)
    {
        $output = XPathEscaper::escapeQuotes($input);

        $this->assertSame($expectedOutput, $output);
    }

    /**
     * @return array[]
     */
    public function xpathProvider()
    {
        return [
            'empty string encapsulate in single quotes' => ['', "''"],
            'string without quotes encapsulate in single quotes' => ['foo bar', "'foo bar'"],
            'string with single quotes encapsulate in double quotes' => ['foo\'bar\'', '"foo\'bar\'"'],
            'string with double quotes encapsulate in single quotes' => ['foo"bar"', '\'foo"bar"\''],
            'string with both types of quotes concatenate' => ['\'"', "concat('', \"'\" ,'\"')"],
            'string with multiple both types of quotes concatenate' => [
                'a \'b\'"c"',
                "concat('a ', \"'\" ,'b', \"'\" ,'\"c\"')",
            ],
        ];
    }
}
