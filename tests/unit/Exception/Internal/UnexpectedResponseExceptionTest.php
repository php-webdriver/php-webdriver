<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use PHPUnit\Framework\TestCase;

class UnexpectedResponseExceptionTest extends TestCase
{
    public function testShouldCreateExceptionForError(): void
    {
        $exception = UnexpectedResponseException::forError('Error message');

        $this->assertSame('Error message', $exception->getMessage());
    }

    public function testShouldCreateExceptionForJsonDecodingError(): void
    {
        $exception = UnexpectedResponseException::forJsonDecodingError(JSON_ERROR_SYNTAX, 'foo');

        $this->assertSame(
            <<<EOF
            JSON decoding of remote response failed.
            Error code: 4
            The response: 'foo'

            EOF,
            $exception->getMessage()
        );
    }
}
