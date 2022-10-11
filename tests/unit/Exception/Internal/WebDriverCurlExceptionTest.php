<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use PHPUnit\Framework\TestCase;

class WebDriverCurlExceptionTest extends TestCase
{
    /**
     * @dataProvider provideParams
     */
    public function testShouldCreateExceptionForCurlError(?array $params, string $exceptionMessageExtra): void
    {
        $exception = WebDriverCurlException::forCurlError('GET', 'http://foo.bar', 'curl error', $params);

        $this->assertSame(
            <<<EOF
            Curl error thrown for http GET to http://foo.bar$exceptionMessageExtra
            
            curl error
            EOF,
            $exception->getMessage()
        );
    }

    public function provideParams(): array
    {
        return [
            'null params' => [null, ''],
            'empty params' => [[], ''],
            'array of params' => [['bar' => 'foo', 'baz' => 'bat'], ' with params: {"bar":"foo","baz":"bat"}'],
        ];
    }
}
