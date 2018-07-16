<?php

namespace Facebook\WebDriver\Support;

use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\TestCase;

class W3CKeysEncoderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getEncodingCases
     * @param $input
     * @param $output
     */
    public function shouldEncodeKeys($input, $output)
    {
        $this->assertEquals($output, W3CKeysEncoder::encode($input));
    }
    
    public function getEncodingCases()
    {
        return [
            [
                'Test',
                ['T', 'e', 's', 't']
            ],
            [
                ['Test', 1],
                ['T', 'e', 's', 't', '1']
            ],
            [
                ['Tet', WebDriverKeys::ARROW_LEFT, 's'],
                ['T', 'e', 't', WebDriverKeys::ARROW_LEFT, 's']
            ],
        ];
    }
}