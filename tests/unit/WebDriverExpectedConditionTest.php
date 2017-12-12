<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\WebDriverExpectedCondition
 */
class WebDriverExpectedConditionTest extends TestCase
{
    /** @var RemoteWebDriver|\PHPUnit_Framework_MockObject_MockObject */
    private $driverMock;
    /** @var WebDriverWait */
    private $wait;

    protected function setUp()
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

        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        $this->driverMock->expects($this->at(1))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element);

        $condition = WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertSame($element, $this->wait->until($condition));
    }

    public function testShouldDetectPresenceOfAllElementsLocatedByCondition()
    {
        $element = $this->createMock(RemoteWebElement::class);

        $this->driverMock->expects($this->at(0))
            ->method('findElements')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn([]);

        $this->driverMock->expects($this->at(1))
            ->method('findElements')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn([$element]);

        $condition = WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.foo'));

        $this->assertSame([$element], $this->wait->until($condition));
    }

    public function testShouldDetectVisibilityOfElementLocatedCondition()
    {
        // Set-up the consecutive calls to apply() as follows:
        // Call #1: throws NoSuchElementException
        // Call #2: return Element, but isDisplayed will throw StaleElementReferenceException
        // Call #3: return Element, but isDisplayed will return false
        // Call #4: return Element, isDisplayed will true and condition will match

        $element = $this->createMock(RemoteWebElement::class);
        $element->expects($this->at(0))
            ->method('isDisplayed')
            ->willThrowException(new StaleElementReferenceException(''));

        $element->expects($this->at(1))
            ->method('isDisplayed')
            ->willReturn(false);

        $element->expects($this->at(2))
            ->method('isDisplayed')
            ->willReturn(true);

        $this->setupDriverToReturnElementAfterAnException($element, 4);

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

        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willReturn($element);

        $element->expects($this->at(0))
            ->method('isDisplayed')
            ->willReturn(true);

        $this->driverMock->expects($this->at(1))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

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

        $element->expects($this->at(0))
            ->method('isDisplayed')
            ->willReturn(true);

        $element->expects($this->at(1))
            ->method('isDisplayed')
            ->willThrowException(new StaleElementReferenceException(''));

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

        $element->expects($this->at(0))
            ->method('isDisplayed')
            ->willReturn(true);

        $element->expects($this->at(1))
            ->method('isDisplayed')
            ->willReturn(false);

        $condition = WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('.foo'));

        $this->assertTrue($this->wait->until($condition));
    }

    public function testShouldDetectVisibilityOfCondition()
    {
        $element = $this->createMock(RemoteWebElement::class);
        $element->expects($this->at(0))
            ->method('isDisplayed')
            ->willReturn(false);

        $element->expects($this->at(1))
            ->method('isDisplayed')
            ->willReturn(true);

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
        $element->expects($this->at(0))
            ->method('getText')
            ->willReturn('this is an old text');

        $element->expects($this->at(1))
            ->method('getText')
            ->willThrowException(new StaleElementReferenceException(''));

        $element->expects($this->at(2))
            ->method('getText')
            ->willReturn('this is a new text');

        $this->setupDriverToReturnElementAfterAnException($element, 4);

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
        $element->expects($this->at(0))
            ->method('getText')
            ->willThrowException(new StaleElementReferenceException(''));

        $element->expects($this->at(1))
            ->method('getText')
            ->willReturn('this is a new text, but not exactly');

        $element->expects($this->at(2))
            ->method('getText')
            ->willReturn('this is a new text');

        $this->setupDriverToReturnElementAfterAnException($element, 4);

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

        $element->expects($this->at(0))
            ->method('getText')
            ->willThrowException(new StaleElementReferenceException(''));

        $element->expects($this->at(1))
            ->method('getText')
            ->willReturn('non-matching');

        $element->expects($this->at(2))
            ->method('getText')
            ->willReturn('matching-123');

        $this->setupDriverToReturnElementAfterAnException($element, 4);

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

        $element->expects($this->at(0))
            ->method('getAttribute')
            ->with('value')
            ->willThrowException(new StaleElementReferenceException(''));

        $element->expects($this->at(1))
            ->method('getAttribute')
            ->with('value')
            ->willReturn('wrong text');

        $element->expects($this->at(2))
            ->method('getAttribute')
            ->with('value')
            ->willReturn('matching text');

        $this->setupDriverToReturnElementAfterAnException($element, 4);

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

    /**
     * @param RemoteWebElement $element
     * @param int $expectedNumberOfFindElementCalls
     */
    private function setupDriverToReturnElementAfterAnException($element, $expectedNumberOfFindElementCalls)
    {
        $this->driverMock->expects($this->at(0))
            ->method('findElement')
            ->with($this->isInstanceOf(WebDriverBy::class))
            ->willThrowException(new NoSuchElementException(''));

        for ($i = 1; $i < $expectedNumberOfFindElementCalls; $i++) {
            $this->driverMock->expects($this->at($i))
                ->method('findElement')
                ->with($this->isInstanceOf(WebDriverBy::class))
                ->willReturn($element);
        }
    }
}
