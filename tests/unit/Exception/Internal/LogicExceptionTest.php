<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use PHPUnit\Framework\TestCase;

class LogicExceptionTest extends TestCase
{
    public function testShouldCreateExceptionForError(): void
    {
        $exception = LogicException::forError('Error message');

        $this->assertSame('Error message', $exception->getMessage());
    }

    public function testShouldCreateExceptionForInvalidHttpMethod(): void
    {
        $exception = LogicException::forInvalidHttpMethod('http://foo.bar', 'FOO', ['key' => 'val']);

        $this->assertSame(
            'The http method called for "http://foo.bar" is "FOO", but it has to be POST if you want to pass'
            . ' the JSON params {"key":"val"}',
            $exception->getMessage()
        );
    }
}
