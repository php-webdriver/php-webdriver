<?php declare(strict_types=1);

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Exception\Internal\LogicException;
use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverSingleKeyAction
 */
class WebDriverSingleKeyActionTest extends TestCase
{
    public function testShouldThrowExceptionWhenNotUsedForModifier(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'keyDown / keyUp actions can only be used for modifier keys, but "foo" was given'
        );

        new WebDriverKeyUpAction(
            $this->createMock(WebDriverKeyboard::class),
            $this->createMock(WebDriverMouse::class),
            $this->createMock(WebDriverLocatable::class),
            'foo'
        );

        $this->assertTrue(true); // To generate coverage, see https://github.com/sebastianbergmann/phpunit/issues/3016
    }
}
