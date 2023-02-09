<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use PHPUnit\Framework\TestCase;

class DriverServerDiedExceptionTest extends TestCase
{
    public function testShouldCreateWithPreviousException(): void
    {
        $dummyPreviousException = UnexpectedResponseException::forError('CURL error');

        $exception = new DriverServerDiedException($dummyPreviousException);

        $this->assertSame($dummyPreviousException, $exception->getPrevious());
    }
}
