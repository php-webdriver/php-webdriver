<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;

/**
 * @covers \Facebook\WebDriver\AbstractWebDriverCheckboxOrRadio
 * @covers \Facebook\WebDriver\WebDriverCheckboxes
 * @group exclude-edge
 */
class WebDriverCheckboxesTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl(TestPage::FORM_CHECKBOX_RADIO));
    }

    public function testIsMultiple()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $this->assertTrue($checkboxes->isMultiple());
    }

    public function testGetOptions()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//form[2]//input[@type="checkbox"]'))
        );

        $this->assertNotEmpty($checkboxes->getOptions());
    }

    public function testGetFirstSelectedOption()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByValue('j2a');

        $this->assertSame('j2a', $checkboxes->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testShouldGetFirstSelectedOptionConsideringOnlyElementsAssociatedWithCurrentForm()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@id="j5b"]'))
        );

        $this->assertEquals('j5b', $checkboxes->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testShouldGetFirstSelectedOptionConsideringOnlyElementsAssociatedWithCurrentFormWithoutId()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@id="j5d"]'))
        );

        $this->assertEquals('j5c', $checkboxes->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testSelectByValue()
    {
        $selectedOptions = ['j2b', 'j2c'];

        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );
        foreach ($selectedOptions as $index => $selectedOption) {
            $checkboxes->selectByValue($selectedOption);
        }

        $selectedValues = [];
        foreach ($checkboxes->getAllSelectedOptions() as $option) {
            $selectedValues[] = $option->getAttribute('value');
        }
        $this->assertSame($selectedOptions, $selectedValues);
    }

    public function testSelectByValueInvalid()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate checkbox with value: notexist');
        $checkboxes->selectByValue('notexist');
    }

    public function testSelectByIndex()
    {
        $selectedOptions = [1 => 'j2b', 2 => 'j2c'];

        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );
        foreach ($selectedOptions as $index => $selectedOption) {
            $checkboxes->selectByIndex($index);
        }

        $selectedValues = [];
        foreach ($checkboxes->getAllSelectedOptions() as $option) {
            $selectedValues[] = $option->getAttribute('value');
        }
        $this->assertSame(array_values($selectedOptions), $selectedValues);
    }

    public function testSelectByIndexInvalid()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate checkbox with index: ' . PHP_INT_MAX);
        $checkboxes->selectByIndex(PHP_INT_MAX);
    }

    /**
     * @dataProvider provideSelectByVisibleTextData
     *
     * @param string $text
     * @param string $value
     */
    public function testSelectByVisibleText($text, $value)
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByVisibleText($text);

        $this->assertSame($value, $checkboxes->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array[]
     */
    public function provideSelectByVisibleTextData()
    {
        return [
            ['J 2 B', 'j2b'],
            ['J2C', 'j2c'],
        ];
    }

    /**
     * @dataProvider provideSelectByVisiblePartialTextData
     *
     * @param string $text
     * @param string $value
     */
    public function testSelectByVisiblePartialText($text, $value)
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByVisiblePartialText($text);

        $this->assertSame($value, $checkboxes->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array[]
     */
    public function provideSelectByVisiblePartialTextData()
    {
        return [
            ['2 B', 'j2b'],
            ['2C', 'j2c'],
        ];
    }

    public function testDeselectAll()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByIndex(0);
        $this->assertCount(1, $checkboxes->getAllSelectedOptions());
        $checkboxes->deselectAll();
        $this->assertEmpty($checkboxes->getAllSelectedOptions());
    }

    public function testDeselectByIndex()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByIndex(0);
        $this->assertCount(1, $checkboxes->getAllSelectedOptions());
        $checkboxes->deselectByIndex(0);
        $this->assertEmpty($checkboxes->getAllSelectedOptions());
    }

    public function testDeselectByValue()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByValue('j2a');
        $this->assertCount(1, $checkboxes->getAllSelectedOptions());
        $checkboxes->deselectByValue('j2a');
        $this->assertEmpty($checkboxes->getAllSelectedOptions());
    }

    public function testDeselectByVisibleText()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByVisibleText('J 2 B');
        $this->assertCount(1, $checkboxes->getAllSelectedOptions());
        $checkboxes->deselectByVisibleText('J 2 B');
        $this->assertEmpty($checkboxes->getAllSelectedOptions());
    }

    public function testDeselectByVisiblePartialText()
    {
        $checkboxes = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]'))
        );

        $checkboxes->selectByVisiblePartialText('2C');
        $this->assertCount(1, $checkboxes->getAllSelectedOptions());
        $checkboxes->deselectByVisiblePartialText('2C');
        $this->assertEmpty($checkboxes->getAllSelectedOptions());
    }
}
