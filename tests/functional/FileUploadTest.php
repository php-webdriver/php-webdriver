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
     */
    public function testShouldUploadAFile()
    {
        $this->driver->get($this->getTestPageUrl('upload.html'));

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

    private function getTestFilePath()
    {
        return __DIR__ . '/Fixtures/FileUploadTestFile.txt';
    }
}
