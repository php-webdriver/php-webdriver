<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use PHPUnit\Framework\TestCase;

/**
 * Unit part of RemoteWebDriver tests. Ie. tests for behavior which do not interact with the real remote server.
 *
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebElement
 */
class RemoteWebElementTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getId
     */
    public function testShouldConstructNewInstance(): void
    {
        $executeMethod = $this->createMock(RemoteExecuteMethod::class);
        $element = new RemoteWebElement($executeMethod, 333);

        $this->assertSame(333, $element->getID());
    }
}
