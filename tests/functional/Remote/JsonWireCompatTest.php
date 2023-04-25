<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\Internal\UnexpectedResponseException;
use PHPUnit\Framework\TestCase;

class JsonWireCompatTest extends TestCase
{
    public function testShouldThrowExceptionWhenElementIsNotArray()
    {
        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Unexpected server response for getting an element. Expected array');

        JsonWireCompat::getElement(null);
    }
}
