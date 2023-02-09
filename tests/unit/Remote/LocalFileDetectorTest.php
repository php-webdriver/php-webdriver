<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use PHPUnit\Framework\TestCase;

class LocalFileDetectorTest extends TestCase
{
    public function testShouldDetectLocalFile(): void
    {
        $detector = new LocalFileDetector();

        $file = $detector->getLocalFile(__DIR__ . '/./' . basename(__FILE__));

        $this->assertSame(__FILE__, $file);
    }

    public function testShouldReturnNullIfFileNotDetected(): void
    {
        $detector = new LocalFileDetector();

        $this->assertNull($detector->getLocalFile('/this/is/not/a/file'));
    }
}
