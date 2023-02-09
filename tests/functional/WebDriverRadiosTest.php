<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\UnsupportedOperationException;

/**
 * @covers \Facebook\WebDriver\AbstractWebDriverCheckboxOrRadio
 * @covers \Facebook\WebDriver\WebDriverRadios
 * @group exclude-edge
 */
class WebDriverRadiosTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl(TestPage::FORM_CHECKBOX_RADIO));
    }

    public function testIsMultiple(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->assertFalse($radios->isMultiple());
    }

    public function testGetOptions(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $values = [];
        foreach ($radios->getOptions() as $option) {
            $values[] = $option->getAttribute('value');
        }

        $this->assertSame(['j3a', 'j3b', 'j3c'], $values);
    }

    public function testGetFirstSelectedOption(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $radios->selectByValue('j3a');

        $this->assertSame('j3a', $radios->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testShouldGetFirstSelectedOptionConsideringOnlyElementsAssociatedWithCurrentForm(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@id="j4b"]')));

        $this->assertEquals('j4b', $radios->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testShouldGetFirstSelectedOptionConsideringOnlyElementsAssociatedWithCurrentFormWithoutId(): void
    {
        $radios = new WebDriverRadios(
            $this->driver->findElement(WebDriverBy::xpath('//input[@id="j4c"]'))
        );

        $this->assertEquals('j4c', $radios->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testSelectByValue(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $radios->selectByValue('j3b');

        $selectedOptions = $radios->getAllSelectedOptions();

        $this->assertCount(1, $selectedOptions);
        $this->assertSame('j3b', $selectedOptions[0]->getAttribute('value'));
    }

    public function testSelectByValueInvalid(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate radio with value: notexist');
        $radios->selectByValue('notexist');
    }

    public function testSelectByIndex(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $radios->selectByIndex(1);

        $allSelectedOptions = $radios->getAllSelectedOptions();
        $this->assertCount(1, $allSelectedOptions);
        $this->assertSame('j3b', $allSelectedOptions[0]->getAttribute('value'));
    }

    public function testSelectByIndexInvalid(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate radio with index: ' . PHP_INT_MAX);
        $radios->selectByIndex(PHP_INT_MAX);
    }

    /**
     * @dataProvider provideSelectByVisibleTextData
     */
    public function testSelectByVisibleText(string $text, string $value): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $radios->selectByVisibleText($text);
        $this->assertSame($value, $radios->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array[]
     */
    public function provideSelectByVisibleTextData(): array
    {
        return [
            ['J 3 B', 'j3b'],
            ['J3C', 'j3c'],
        ];
    }

    /**
     * @dataProvider provideSelectByVisiblePartialTextData
     */
    public function testSelectByVisiblePartialText(string $text, string $value): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $radios->selectByVisiblePartialText($text);
        $this->assertSame($value, $radios->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array[]
     */
    public function provideSelectByVisiblePartialTextData(): array
    {
        return [
            ['3 B', 'j3b'],
            ['3C', 'j3c'],
        ];
    }

    public function testDeselectAllRadio(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $radios->deselectAll();
    }

    public function testDeselectByIndexRadio(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $radios->deselectByIndex(0);
    }

    public function testDeselectByValueRadio(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $radios->deselectByValue('val');
    }

    public function testDeselectByVisibleTextRadio(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $radios->deselectByVisibleText('AB');
    }

    public function testDeselectByVisiblePartialTextRadio(): void
    {
        $radios = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $radios->deselectByVisiblePartialText('AB');
    }
}
