<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\LocalFileDetector;

/**
 * @covers \Facebook\WebDriver\Remote\LocalFileDetector
 * @covers \Facebook\WebDriver\Remote\RemoteWebElement
 */
class FileUploadTest extends WebDriverTestCase
{
    /**
     * @group exclude-edge
     * https://developer.microsoft.com/en-us/microsoft-edge/platform/issues/6052385/
     * @group exclude-safari
     * The test times out on safari.
     * @group exclude-saucelabs
     * W3C protocol does not support remote file upload: https://github.com/w3c/webdriver/issues/1355
     */
    public function testShouldUploadAFile(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::UPLOAD));

        $fileElement = $this->driver->findElement(WebDriverBy::name('upload'));

        $fileElement->setFileDetector(new LocalFileDetector())
            ->sendKeys($this->getTestFilePath());

        $fileElement->submit();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::titleIs('File upload endpoint')
        );

        $uploadedFilesList = $this->driver->findElements(WebDriverBy::cssSelector('ul.uploaded-files li'));
        $this->assertCount(1, $uploadedFilesList);

        $uploadedFileName = $this->driver->findElement(WebDriverBy::cssSelector('ul.uploaded-files li span.file-name'))
            ->getText();
        $uploadedFileSize = $this->driver->findElement(WebDriverBy::cssSelector('ul.uploaded-files li span.file-size'))
            ->getText();

        $this->assertSame('FileUploadTestFile.txt', $uploadedFileName);
        $this->assertSame('10', $uploadedFileSize);
    }

    private function getTestFilePath(): string
    {
        return __DIR__ . '/Fixtures/FileUploadTestFile.txt';
    }
}
