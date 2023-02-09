<?php declare(strict_types=1);

namespace Facebook\WebDriver\Support;

use PHPUnit\Framework\TestCase;

class XPathEscaperTest extends TestCase
{
    /**
     * @dataProvider provideXpath
     */
    public function testShouldInstantiateWithCapabilitiesGivenInConstructor(string $input, string $expectedOutput): void
    {
        $output = XPathEscaper::escapeQuotes($input);

        $this->assertSame($expectedOutput, $output);
    }

    /**
     * @return array[]
     */
    public function provideXpath(): array
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
