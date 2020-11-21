<?php

namespace Facebook\WebDriver\Exception;

use PHPUnit\Framework\TestCase;

class DriverServerDiedExceptionTest extends TestCase
{
    public function testShouldCreateWithPreviousException()
    {
        $dummyPreviousException = new WebDriverCurlException('CURL error');

        $exception = new DriverServerDiedException($dummyPreviousException);

        $this->assertSame($dummyPreviousException, $exception->getPrevious());
    }
}
