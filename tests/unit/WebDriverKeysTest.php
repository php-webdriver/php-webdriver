<?php

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
