<?php declare(strict_types=1);

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
     */
    public function testShouldEncodeKeysToFormatOfEachProtocol(
        $keys,
        array $expectedOssOutput,
        string $expectedW3cOutput
    ): void {
        $this->assertSame($expectedOssOutput, WebDriverKeys::encode($keys));
        $this->assertSame($expectedW3cOutput, WebDriverKeys::encode($keys, true));
    }

    /**
     * @return array[]
     */
    public function provideKeys(): array
    {
        return [
            'empty string' => ['', [''], ''],
            'simple string' => ['foo', ['foo'], 'foo'],
            'string as an array' => [['foo'], ['foo'], 'foo'],
            'string with modifier as an array' => [
                [WebDriverKeys::SHIFT, 'foo'],
                [WebDriverKeys::SHIFT, 'foo'],
                WebDriverKeys::SHIFT . 'foo',
            ],
            'string with concatenated modifier' => [
                [WebDriverKeys::SHIFT . 'foo'],
                [WebDriverKeys::SHIFT . 'foo'],
                WebDriverKeys::SHIFT . 'foo',
            ],
            'simple numeric value' => [3, ['3'], '3'],
            'multiple numeric values' => [[1, 3.33], ['1', '3.33'], '13.33'],
            'multiple mixed values ' => [
                ['foo', WebDriverKeys::END, '1.234'],
                ['foo', WebDriverKeys::END, '1.234'],
                'foo' . WebDriverKeys::END . '1.234',
            ],
            'array of strings with modifiers should separate them with NULL character' => [
                [[WebDriverKeys::SHIFT, 'foo'], [WebDriverKeys::META, 'bar']],
                [WebDriverKeys::SHIFT . 'foo' . WebDriverKeys::NULL, WebDriverKeys::META . 'bar' . WebDriverKeys::NULL],
                WebDriverKeys::SHIFT . 'foo' . WebDriverKeys::NULL . WebDriverKeys::META . 'bar' . WebDriverKeys::NULL,
            ],
            'null' => [null, [], ''],
        ];
    }
}
