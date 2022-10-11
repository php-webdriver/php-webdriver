<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use PHPUnit\Framework\TestCase;

class IOExceptionTest extends TestCase
{
    public function testShouldCreateExceptionForFileError(): void
    {
        $exception = IOException::forFileError('Error message', '/file/path.txt');

        $this->assertSame('Error message ("/file/path.txt")', $exception->getMessage());
    }
}
