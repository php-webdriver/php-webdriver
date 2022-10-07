<?php

namespace Facebook\WebDriver\Support;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;
use PHPUnit\Framework\TestCase;

class ScreenshotHelperTest extends TestCase
{
    const BLACK_PIXEL = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    public function testShouldSavePageScreenshotToSubdirectoryIfNotExists()
    {
        $fullFilePath = sys_get_temp_dir() . '/' . uniqid('php-webdriver-', true) . '/screenshot.png';
        $directoryPath = dirname($fullFilePath);
        $this->assertDirectoryNotExists($directoryPath);

        $executorMock = $this->createMock(RemoteExecuteMethod::class);
        $executorMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(DriverCommand::SCREENSHOT))
            ->willReturn(self::BLACK_PIXEL);

        $helper = new ScreenshotHelper($executorMock);
        $output = $helper->takePageScreenshot($fullFilePath);

        $this->assertSame(base64_decode(self::BLACK_PIXEL, true), $output);

        $this->assertDirectoryExists($directoryPath);
        $this->assertFileExists($fullFilePath);

        unlink($fullFilePath);
        rmdir($directoryPath);
    }

    public function testShouldOnlyReturnBase64IfDirectoryNotProvided()
    {
        $executorMock = $this->createMock(RemoteExecuteMethod::class);
        $executorMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(DriverCommand::SCREENSHOT))
            ->willReturn(self::BLACK_PIXEL);

        $helper = new ScreenshotHelper($executorMock);
        $output = $helper->takePageScreenshot();

        $this->assertSame(base64_decode(self::BLACK_PIXEL, true), $output);
    }

    public function testShouldSaveElementScreenshotToSubdirectoryIfNotExists()
    {
        $fullFilePath = sys_get_temp_dir() . '/' . uniqid('php-webdriver-', true) . '/screenshot.png';
        $directoryPath = dirname($fullFilePath);
        $this->assertDirectoryNotExists($directoryPath);
        $elementId = 'foo-id';

        $executorMock = $this->createMock(RemoteExecuteMethod::class);
        $executorMock->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::TAKE_ELEMENT_SCREENSHOT, [':id' => $elementId])
            ->willReturn(self::BLACK_PIXEL);

        $helper = new ScreenshotHelper($executorMock);
        $output = $helper->takeElementScreenshot($elementId, $fullFilePath);

        $this->assertSame(base64_decode(self::BLACK_PIXEL, true), $output);
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileExists($fullFilePath);

        unlink($fullFilePath);
        rmdir($directoryPath);
    }

    /**
     * @dataProvider provideInvalidData
     * @param mixed $data
     * @param string $expectedExceptionMessage
     */
    public function testShouldThrowExceptionWhenInvalidDataReceived($data, $expectedExceptionMessage)
    {
        $executorMock = $this->createMock(RemoteExecuteMethod::class);
        $executorMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(DriverCommand::SCREENSHOT))
            ->willReturn($data);

        $helper = new ScreenshotHelper($executorMock);

        $this->expectException(WebDriverException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $helper->takePageScreenshot();
    }

    public function provideInvalidData()
    {
        return [
            'empty response' => [null, 'Error taking screenshot, no data received from the remote end'],
            'not valid base64 response' => ['invalid%base64', 'Error decoding screenshot data'],
        ];
    }
}
