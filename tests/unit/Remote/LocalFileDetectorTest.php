<?php

namespace Facebook\WebDriver\Remote;

use PHPUnit\Framework\TestCase;

class LocalFileDetectorTest extends TestCase
{
    public function testShouldDetectLocalFile()
    {
        $detector = new LocalFileDetector();

        $file = $detector->getLocalFile(__DIR__ . '/./' . basename(__FILE__));

        $this->assertSame(__FILE__, $file);
    }

    public function testShouldReturnNullIfFileNotDetected()
    {
        $detector = new LocalFileDetector();

        $this->assertNull($detector->getLocalFile('/this/is/not/a/file'));
    }
}
