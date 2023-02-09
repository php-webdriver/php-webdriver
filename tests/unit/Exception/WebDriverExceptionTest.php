<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception;

use PHPUnit\Framework\TestCase;

class WebDriverExceptionTest extends TestCase
{
    public function testShouldStoreResultsOnInstantiation(): void
    {
        $exception = new WebDriverException('exception message', ['foo', 'bar']);

        $this->assertInstanceOf(WebDriverException::class, $exception);
        $this->assertSame('exception message', $exception->getMessage());
        $this->assertSame(['foo', 'bar'], $exception->getResults());
    }

    /**
     * @dataProvider provideJsonWireStatusCode
     * @dataProvider provideW3CWebDriverErrorCode
     * @param int|string $errorCode
     */
    public function testShouldThrowProperExceptionBasedOnWebDriverErrorCode(
        $errorCode,
        string $expectedExceptionType
    ): void {
        try {
            WebDriverException::throwException($errorCode, 'exception message', ['results']);
        } catch (WebDriverException $e) {
            $this->assertInstanceOf($expectedExceptionType, $e);

            $this->assertSame('exception message', $e->getMessage());
            $this->assertSame(['results'], $e->getResults());
        }
    }

    /**
     * @return array[]
     */
    public function provideW3CWebDriverErrorCode(): array
    {
        return [
                ['element click intercepted', ElementClickInterceptedException::class],
                ['element not interactable', ElementNotInteractableException::class],
                ['element not interactable', ElementNotInteractableException::class],
                ['insecure certificate', InsecureCertificateException::class],
                ['invalid argument', InvalidArgumentException::class],
                ['invalid cookie domain', InvalidCookieDomainException::class],
                ['invalid element state', InvalidElementStateException::class],
                ['invalid selector', InvalidSelectorException::class],
                ['invalid session id', InvalidSessionIdException::class],
                ['javascript error', JavascriptErrorException::class],
                ['move target out of bounds', MoveTargetOutOfBoundsException::class],
                ['no such alert', NoSuchAlertException::class],
                ['no such cookie', NoSuchCookieException::class],
                ['no such element', NoSuchElementException::class],
                ['no such frame', NoSuchFrameException::class],
                ['no such window', NoSuchWindowException::class],
                ['script timeout', ScriptTimeoutException::class],
                ['session not created', SessionNotCreatedException::class],
                ['stale element reference', StaleElementReferenceException::class],
                ['timeout', TimeoutException::class],
                ['unable to set cookie', UnableToSetCookieException::class],
                ['unable to capture screen', UnableToCaptureScreenException::class],
                ['unexpected alert open', UnexpectedAlertOpenException::class],
                ['unknown command', UnknownCommandException::class],
                ['unknown error', UnknownErrorException::class],
                ['unknown method', UnknownMethodException::class],
                ['unsupported operation', UnsupportedOperationException::class],
        ];
    }

    /**
     * @return array[]
     */
    public function provideJsonWireStatusCode(): array
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
            [21, TimeoutException::class],
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
