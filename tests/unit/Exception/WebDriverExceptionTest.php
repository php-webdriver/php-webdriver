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

namespace Facebook\WebDriver\Exception;

use PHPUnit\Framework\TestCase;

class WebDriverExceptionTest extends TestCase
{
    public function testShouldStoreResultsOnInstantiation()
    {
        $exception = new WebDriverException('exception message', ['foo', 'bar']);

        $this->assertInstanceOf(WebDriverException::class, $exception);
        $this->assertSame('exception message', $exception->getMessage());
        $this->assertSame(['foo', 'bar'], $exception->getResults());
    }

    /**
     * @dataProvider statusCodeProvider
     * @param int $statusCode
     * @param string $expectedExceptionType
     */
    public function testShouldThrowProperExceptionBasedOnSeleniumStatusCode($statusCode, $expectedExceptionType)
    {
        try {
            WebDriverException::throwException($statusCode, 'exception message', ['results']);
        } catch (WebDriverException $e) {
            $this->assertInstanceOf($expectedExceptionType, $e);

            $this->assertSame('exception message', $e->getMessage());
            $this->assertSame(['results'], $e->getResults());
        }
    }

    /**
     * @return array[]
     */
    public function statusCodeProvider()
    {
        return [
            [1337, UnrecognizedExceptionException::class],
            [1, IndexOutOfBoundsException::class],
            [2, NoCollectionException::class],
            [3, NoStringException::class],
            [4, NoStringLengthException::class],
            [5, NoStringWrapperException::class],
            [6, NoSuchDriverException::class],
            [7, NoSuchElementException::class],
            [8, NoSuchFrameException::class],
            [9, UnknownCommandException::class],
            [10, StaleElementReferenceException::class],
            [11, ElementNotVisibleException::class],
            [12, InvalidElementStateException::class],
            [13, UnknownServerException::class],
            [14, ExpectedException::class],
            [15, ElementNotSelectableException::class],
            [16, NoSuchDocumentException::class],
            [17, UnexpectedJavascriptException::class],
            [18, NoScriptResultException::class],
            [19, XPathLookupException::class],
            [20, NoSuchCollectionException::class],
            [21, TimeOutException::class],
            [22, NullPointerException::class],
            [23, NoSuchWindowException::class],
            [24, InvalidCookieDomainException::class],
            [25, UnableToSetCookieException::class],
            [26, UnexpectedAlertOpenException::class],
            [27, NoAlertOpenException::class],
            [28, ScriptTimeoutException::class],
            [29, InvalidCoordinatesException::class],
            [30, IMENotAvailableException::class],
            [31, IMEEngineActivationFailedException::class],
            [32, InvalidSelectorException::class],
            [33, SessionNotCreatedException::class],
            [34, MoveTargetOutOfBoundsException::class],
        ];
    }
}
