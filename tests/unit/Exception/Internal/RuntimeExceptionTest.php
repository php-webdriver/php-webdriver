<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class RuntimeExceptionTest extends TestCase
{
    public function testShouldCreateExceptionForError(): void
    {
        $exception = RuntimeException::forError('Error message');

        $this->assertSame('Error message', $exception->getMessage());
    }

    public function testShouldCreateExceptionForDriverError(): void
    {
        $processMock = $this->createConfiguredMock(
            Process::class,
            [
                'getCommandLine' => '/bin/true --force',
                'getErrorOutput' => 'may the force be with you',
            ]
        );

        $exception = RuntimeException::forDriverError($processMock);

        $this->assertSame(
            'Error starting driver executable "/bin/true --force": may the force be with you',
            $exception->getMessage()
        );
    }
}
