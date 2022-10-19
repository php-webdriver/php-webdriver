<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\WebDriverExpectedCondition
 */
class WebDriverExpectedConditionTest extends TestCase
{
    /** @var RemoteWebDriver|\PHPUnit\Framework\MockObject\MockObject */
    private $driverMock;
    /** @var WebDriverWait */
    private $wait;

    protected function setUp(): void
    {
        $this->driverMock = $this->createMock(RemoteWebDriver::class);
        $this->wait = new WebDriverWait($this->driverMock, 1, 1);
    }

    public function testShouldDetectTitleIsCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getTitle')
            ->willReturnOnConsecutiveCalls('old', 'oldwithnew', 'new');

        $condition = WebDriverExpectedCondition::titleIs('new');

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectTitleContainsCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getTitle')
            ->willReturnOnConsecutiveCalls('old', 'oldwithnew', 'new');

        $condition = WebDriverExpectedCondition::titleContains('new');

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectTitleMatchesCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getTitle')
            ->willReturnOnConsecutiveCalls('non-matching', 'matching-not', 'matching-123');

        $condition = WebDriverExpectedCondition::titleMatches('/matching-\d{3}/');

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectUrlIsCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getCurrentURL')
            ->willReturnOnConsecutiveCalls('https://old/', 'https://oldwithnew/', 'https://new/');

        $condition = WebDriverExpectedCondition::urlIs('https://new/');

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectUrlContainsCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getCurrentURL')
            ->willReturnOnConsecutiveCalls('https://old/', 'https://oldwithnew/', 'https://new/');

        $condition = WebDriverExpectedCondition::urlContains('new');

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectUrlMatchesCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getCurrentURL')
            ->willReturnOnConsecutiveCalls('https://non/matching/', 'https://matching/not/', 'https://matching/123/');

        $condition = WebDriverExpectedCondition::urlMatches('/matching\/\d{3}\/$/');

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectPresenceOfElementLocatedCondition()
    {
        $element = new RemoteWebElement(new RemoteExecuteMethod($this->driverMock), 'id');

        $this->driverMock->expects($this->exactly(2))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new NoSuchElementException('')),
                $element
            );

        $condition = WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertSame($element, $this->wait->until($condition));
    }

    public function testShouldDetectNotPresenceOfElementLocatedCondition()
    {
        $element = new RemoteWebElement(new RemoteExecuteMethod($this->driverMock), 'id');

        $this->driverMock->expects($this->exactly(2))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturnOnConsecutiveCalls(
                $element,
                $this->throwException(new NoSuchElementException(''))
            );

        $condition = WebDriverExpectedCondition::not(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo'))
        );

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testShouldDetectPresenceOfAllElementsLocatedByCondition()
    {
        $element = $this->createMock(RemoteWebElement::class);

        $this->driverMock->expects($this->exactly(2))
            ->method('findElements')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturnOnConsecutiveCalls(
                [],
                [$element]
            );

        $condition = WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.foo'));

        $this->assertSame([$element], $this->wait->until($condition));
    }

    public function testShouldDetectVisibilityOfElementLocatedCondition()
    {
        // Set-up the consecutive calls to apply() as follows:
        // Call #1: throws NoSuchElementException
        // Call #2: return Element, but isDisplayed will throw StaleElementReferenceException
        // Call #3: return Element, but isDisplayed will return false
        // Call #4: return Element, isDisplayed will return true and condition will match

        $element = $this->createMock(RemoteWebElement::class);
        $element->expects($this->exactly(3))
            ->method('isDisplayed')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new StaleElementReferenceException('')),
                false,
                true
            );

        $this->setupDriverToReturnElementAfterAnException($element, 3);

        $condition = WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertSame($element, $this->wait->until($condition));
    }

    public function testShouldDetectVisibilityOfAnyElementLocated()
    {
        $elementList = [
            $this->createMock(RemoteWebElement::class),
            $this->createMock(RemoteWebElement::class),
            $this->createMock(RemoteWebElement::class),
        ];

        $elementList[0]->expects($this->once())
            ->method('isDisplayed')
            ->willReturn(false);

        $elementList[1]->expects($this->once())
            ->method('isDisplayed')
            ->willReturn(true);

        $elementList[2]->expects($this->once())
            ->method('isDisplayed')
            ->willReturn(true);

        $this->driverMock->expects($this->once())
            ->method('findElements')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($elementList);

        $condition = WebDriverExpectedCondition::visibilityOfAnyElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertSame([$elementList[1], $elementList[2]], $this->wait->until($condition));
    }

    public function testShouldDetectInvisibilityOfElementLocatedConditionOnNoSuchElementException()
    {
        $element = $this->createMock(RemoteWebElement::class);

        $this->driverMock->expects($this->exactly(2))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn(
                $element,
                $this->throwException(new NoSuchElementException(''))
            );

        $element->expects($this->once())
            ->method('isDisplayed')
            ->willReturn(true);

        $condition = WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectInvisibilityOfElementLocatedConditionOnStaleElementReferenceException()
    {
        $element = $this->createMock(RemoteWebElement::class);

        $this->driverMock->expects($this->exactly(2))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element);

        $element->expects($this->exactly(2))
            ->method('isDisplayed')
            ->willReturnOnConsecutiveCalls(
                true,
                $this->throwException(new StaleElementReferenceException(''))
            );

        $condition = WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectInvisibilityOfElementLocatedConditionWhenElementBecamesInvisible()
    {
        $element = $this->createMock(RemoteWebElement::class);

        $this->driverMock->expects($this->exactly(2))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element);

        $element->expects($this->exactly(2))
            ->method('isDisplayed')
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $condition = WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectVisibilityOfCondition()
    {
        $element = $this->createMock(RemoteWebElement::class);
        $element->expects($this->exactly(2))
            ->method('isDisplayed')
            ->willReturn(
                false,
                true
            );

        $condition = WebDriverExpectedCondition::visibilityOf($element);

        $this->assertSame($element, $this->wait->until($condition));
    }

    public function testShouldDetectElementTextContainsCondition()
    {
        // Set-up the consecutive calls to apply() as follows:
        // Call #1: throws NoSuchElementException
        // Call #2: return Element, but getText returns an old text
        // Call #3: return Element, but getText will throw StaleElementReferenceException
        // Call #4: return Element, getText will return new text and condition will match

        $element = $this->createMock(RemoteWebElement::class);
        $element->expects($this->exactly(3))
            ->method('getText')
            ->willReturnOnConsecutiveCalls(
                'this is an old text',
                $this->throwException(new StaleElementReferenceException('')),
                'this is a new text'
            );

        $this->setupDriverToReturnElementAfterAnException($element, 3);

        $condition = WebDriverExpectedCondition::elementTextContains(WebDriverBy::cssSelector('.foo'), 'new');

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectElementTextIsCondition()
    {
        // Set-up the consecutive calls to apply() as follows:
        // Call #1: throws NoSuchElementException
        // Call #2: return Element, but getText will throw StaleElementReferenceException
        // Call #3: return Element, getText will return not-matching text
        // Call #4: return Element, getText will return new text and condition will match

        $element = $this->createMock(RemoteWebElement::class);
        $element->expects($this->exactly(3))
            ->method('getText')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new StaleElementReferenceException('')),
                'this is a new text, but not exactly',
                'this is a new text'
            );

        $this->setupDriverToReturnElementAfterAnException($element, 3);

        $condition = WebDriverExpectedCondition::elementTextIs(
            WebDriverBy::cssSelector('.foo'),
            'this is a new text'
        );

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectElementTextMatchesCondition()
    {
        // Set-up the consecutive calls to apply() as follows:
        // Call #1: throws NoSuchElementException
        // Call #2: return Element, but getText will throw StaleElementReferenceException
        // Call #3: return Element, getText will return not-matching text
        // Call #4: return Element, getText will return matching text

        $element = $this->createMock(RemoteWebElement::class);

        $element->expects($this->exactly(3))
            ->method('getText')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new StaleElementReferenceException('')),
                'non-matching',
                'matching-123'
            );

        $this->setupDriverToReturnElementAfterAnException($element, 3);

        $condition = WebDriverExpectedCondition::elementTextMatches(
            WebDriverBy::cssSelector('.foo'),
            '/matching-\d{3}/'
        );

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectElementValueContainsCondition()
    {
        // Set-up the consecutive calls to apply() as follows:
        // Call #1: throws NoSuchElementException
        // Call #2: return Element, but getAttribute will throw StaleElementReferenceException
        // Call #3: return Element, getAttribute('value') will return not-matching text
        // Call #4: return Element, getAttribute('value') will return matching text

        $element = $this->createMock(RemoteWebElement::class);

        $element->expects($this->exactly(3))
            ->method('getAttribute')
            ->with('value')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new StaleElementReferenceException('')),
                'wrong text',
                'matching text'
            );

        $this->setupDriverToReturnElementAfterAnException($element, 3);

        $condition = WebDriverExpectedCondition::elementValueContains(
            WebDriverBy::cssSelector('.foo'),
            'matching'
        );

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectNumberOfWindowsToBeCondition()
    {
        $this->driverMock->expects($this->any())
            ->method('getWindowHandles')
            ->willReturnOnConsecutiveCalls(['one'], ['one', 'two', 'three'], ['one', 'two']);

        $condition = WebDriverExpectedCondition::numberOfWindowsToBe(2);

        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertFalse(call_user_func($condition->getApply(), $this->driverMock));
        $this->assertTrue(call_user_func($condition->getApply(), $this->driverMock));
    }

    public function testAllCondition()
    {
        $element1 = new RemoteWebElement(new RemoteExecuteMethod($this->driverMock), 'id1');
        $element2 = new RemoteWebElement(new RemoteExecuteMethod($this->driverMock), 'id2');

        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        $this->driverMock->expects($this->at(1))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element2);

        $this->driverMock->expects($this->at(3))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element1);

        $this->driverMock->expects($this->at(4))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element2);

        $condition = WebDriverExpectedCondition::all([
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo')),
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.bar')),
        ]);

        $result = $this->wait->until($condition);
        $this->assertInternalType('array', $result);
        $this->assertSame($element1, $result[0]);
        $this->assertSame($element2, $result[1]);
    }

    public function testAllConditionFailure()
    {
        $element = new RemoteWebElement(new RemoteExecuteMethod($this->driverMock), 'id');

        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        $this->driverMock->expects($this->at(1))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element);

        $this->expectException(TimeoutException::class);

        $condition = WebDriverExpectedCondition::all([
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo')),
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.bar')),
        ]);

        $this->wait->until($condition);
    }

    public function testAnyOfCondition()
    {
        $element = new RemoteWebElement(new RemoteExecuteMethod($this->driverMock), 'id');

        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        $this->driverMock->expects($this->at(1))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element);

        $condition = WebDriverExpectedCondition::any([
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo')),
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.bar')),
        ]);

        $this->assertSame($element, $this->wait->until($condition));
    }

    public function testAnyOfConditionFailure()
    {
        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        $this->driverMock->expects($this->at(1))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        $this->expectException(TimeoutException::class);

        $condition = WebDriverExpectedCondition::any([
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo')),
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.bar')),
        ]);

        $this->wait->until($condition);
    }

    /**
     * @param RemoteWebElement $element
     * @param int $expectedNumberOfFindElementCalls
     */
    private function setupDriverToReturnElementAfterAnException($element, $expectedNumberOfFindElementCalls)
    {
        $consecutiveReturn = [
            $this->throwException(new NoSuchElementException('')),
        ];

        for ($i = 0; $i < $expectedNumberOfFindElementCalls; $i++) {
            $consecutiveReturn[] = $element;
        }

        $this->driverMock->expects($this->exactly(count($consecutiveReturn)))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturnOnConsecutiveCalls(...$consecutiveReturn);
    }
}
