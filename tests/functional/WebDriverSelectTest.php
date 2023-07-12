<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\Exception\UnsupportedOperationException;

/**
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Exception\UnexpectedTagNameException
 * @covers \Facebook\WebDriver\WebDriverSelect
 */
class WebDriverSelectTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl(TestPage::FORM));
    }

    /**
     * @dataProvider multipleSelectDataProvider
     */
    public function testShouldCreateNewInstanceForSelectElementAndDetectIfItIsMultiple(string $selector): void
    {
        $originalElement = $this->driver->findElement(WebDriverBy::cssSelector('#select'));
        $originalMultipleElement = $this->driver->findElement(WebDriverBy::cssSelector($selector));

        $select = new WebDriverSelect($originalElement);
        $selectMultiple = new WebDriverSelect($originalMultipleElement);

        $this->assertInstanceOf(WebDriverSelect::class, $select);
        $this->assertFalse($select->isMultiple());

        $this->assertInstanceOf(WebDriverSelect::class, $selectMultiple);
        $this->assertTrue($selectMultiple->isMultiple());
    }

    public static function multipleSelectDataProvider(): array
    {
        return [
            ['#select-multiple'],
            ['#select-multiple-2'],
            ['#select-multiple-3'],
        ];
    }

    public function testShouldThrowExceptionWhenNotInstantiatedOnSelectElement(): void
    {
        $notSelectElement = $this->driver->findElement(WebDriverBy::cssSelector('textarea'));

        $this->expectException(UnexpectedTagNameException::class);
        $this->expectExceptionMessage('Element should have been "select" but was "textarea"');
        new WebDriverSelect($notSelectElement);
    }

    /**
     * @dataProvider provideSelectSelector
     */
    public function testShouldGetOptionsOfSelect(string $selector): void
    {
        $originalElement = $this->driver->findElement(WebDriverBy::cssSelector($selector));
        $select = new WebDriverSelect($originalElement);

        $options = $select->getOptions();

        $this->assertContainsOnlyInstancesOf(WebDriverElement::class, $options);
        $this->assertCount(5, $options);
    }

    /**
     * @return array[]
     */
    public function provideSelectSelector(): array
    {
        return [
            'simple <select>' => ['#select'],
            '<select> with multiple attribute' => ['#select-multiple'],
        ];
    }

    public function testShouldDefaultSelectedOptionOfSimpleSelect(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $selectedOptions = $select->getAllSelectedOptions();
        $firstSelectedOption = $select->getFirstSelectedOption();

        $this->assertContainsOnlyInstancesOf(WebDriverElement::class, $selectedOptions);
        $this->assertCount(1, $selectedOptions);
        $this->assertSame('First', $selectedOptions[0]->getText());

        $this->assertInstanceOf(WebDriverElement::class, $firstSelectedOption);
        $this->assertSame('First', $firstSelectedOption->getText());
    }

    public function testShouldReturnEmptyArrayIfNoOptionsOfMultipleSelectSelected(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();

        $selectedOptions = $select->getAllSelectedOptions();

        $this->assertSame([], $selectedOptions);
    }

    public function testShouldThrowExceptionIfThereIsNoFirstSelectedOptionOfMultipleSelect(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('No options are selected');
        $select->getFirstSelectedOption();
    }

    public function testShouldSelectOptionOfSimpleSelectByIndex(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();
        $this->assertSame('first', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByIndex(1);
        $select->selectByIndex(1); // should be selected even if selected again
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByIndex(3);
        $this->assertSame('fourth', $select->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldSelectOptionOfMultipleSelectByIndex(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $this->assertSame([], $select->getAllSelectedOptions());

        $select->selectByIndex(1);
        $select->selectByIndex(1); // should be selected even if selected again
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));
        $this->assertContainsOptionsWithValues(['second'], $select->getAllSelectedOptions());

        $select->selectByIndex(4);
        $select->selectByIndex(3);
        // the first selected option is still the same
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));

        $this->assertContainsOptionsWithValues(['second', 'fourth', 'fifth'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfThereIsNoOptionIndexToSelect(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate option with index: 1337');
        $select->selectByIndex(1337);
    }

    public function testShouldSelectOptionOfSimpleSelectByValue(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();
        $this->assertSame('first', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByValue('second');
        $select->selectByValue('second'); // should be selected even if selected again
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByValue('fourth');
        $this->assertSame('fourth', $select->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldSelectOptionOfMultipleSelectByValue(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $this->assertSame([], $select->getAllSelectedOptions());

        $select->selectByValue('second');
        $select->selectByValue('second'); // should be selected even if selected again
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));
        $this->assertContainsOptionsWithValues(['second'], $select->getAllSelectedOptions());

        $select->selectByValue('fifth');
        $select->selectByValue('fourth');
        // the first selected option is still the same
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));

        $this->assertContainsOptionsWithValues(['second', 'fourth', 'fifth'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfThereIsNoOptionValueToSelect(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate option with value: 1337');
        $select->selectByValue(1337);
    }

    public function testShouldSelectOptionOfSimpleSelectByVisibleText(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();
        $this->assertSame('first', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByVisibleText('Fourth with spaces inside');
        $select->selectByVisibleText('Fourth with spaces inside'); // should be selected even if selected again
        $this->assertSame('fourth', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByVisibleText('Fifth surrounded by spaces');
        $this->assertSame('fifth', $select->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldSelectOptionOfMultipleSelectByVisibleText(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $this->assertSame([], $select->getAllSelectedOptions());

        $select->selectByVisibleText('This is second option');
        $select->selectByVisibleText('This is second option');  // should be selected even if selected again
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));
        $this->assertContainsOptionsWithValues(['second'], $select->getAllSelectedOptions());

        $select->selectByVisibleText('Fifth surrounded by spaces');
        $select->selectByVisibleText('Fourth with spaces inside');
        // the first selected option is still the same
        $this->assertSame('second', $select->getFirstSelectedOption()->getAttribute('value'));

        $this->assertContainsOptionsWithValues(['second', 'fourth', 'fifth'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfThereIsNoOptionVisibleTextToSelect(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate option with text: second');
        $select->selectByVisibleText('second'); // the option is "This is second option"
    }

    public function testShouldSelectOptionOfSimpleSelectByVisiblePartialText(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();
        $this->assertSame('first', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByVisiblePartialText('not second');
        $this->assertSame('third', $select->getFirstSelectedOption()->getAttribute('value'));

        $select->selectByVisiblePartialText('Fourth with spaces');
        $select->selectByVisiblePartialText('Fourth with spaces'); // should be selected even if selected again
        $this->assertSame('fourth', $select->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldSelectOptionOfMultipleSelectByVisiblePartialText(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $this->assertSame([], $select->getAllSelectedOptions());

        $select->selectByVisiblePartialText('Firs');
        $select->selectByVisiblePartialText('Firs'); // should be selected even if selected again
        $this->assertSame('first', $select->getFirstSelectedOption()->getAttribute('value'));
        $this->assertContainsOptionsWithValues(['first'], $select->getAllSelectedOptions());

        $select->selectByVisiblePartialText('second'); // matches options 'second' and 'third'
        $select->selectByVisiblePartialText('Fourth with spaces');
        // the first selected option is still the same
        $this->assertSame('first', $select->getFirstSelectedOption()->getAttribute('value'));

        $this->assertContainsOptionsWithValues(
            ['first', 'second', 'third', 'fourth'],
            $select->getAllSelectedOptions()
        );
    }

    public function testShouldThrowExceptionIfThereIsNoOptionVisiblePartialTextToSelect(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate option with text: Not existing option');
        $select->selectByVisiblePartialText('Not existing option');
    }

    public function testShouldThrowExceptionWhenDeselectingOnSimpleSelect(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect all options of a multi-select');
        $select->deselectAll();
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldDeselectAllOptionsOnMultipleSelect(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();

        $select->selectByIndex(1);
        $select->selectByIndex(3);
        $select->selectByIndex(4);
        $this->assertCount(3, $select->getAllSelectedOptions());

        $select->deselectAll();

        $this->assertCount(0, $select->getAllSelectedOptions());
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldDeselectOptionOnMultipleSelectByIndex(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $select->selectByValue('fourth'); // index 3
        $select->selectByValue('second'); // index 1

        $select->deselectByIndex(3);
        $select->deselectByIndex(3); // should be deselected even if deselected again
        $select->deselectByIndex(4); // should not select unselected option

        $this->assertContainsOptionsWithValues(['second'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfDeselectingSimpleSelectByIndex(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect options of a multi-select');
        $select->deselectByIndex(0);
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldDeselectOptionOnMultipleSelectByValue(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $select->selectByValue('third');
        $select->selectByValue('first');

        $select->deselectByValue('third');
        $select->deselectByValue('third'); // should be deselected even if deselected again
        $select->deselectByValue('second'); // should not select unselected option

        $this->assertContainsOptionsWithValues(['first'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfDeselectingSimpleSelectByValue(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect options of a multi-select');
        $select->deselectByValue('first');
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldDeselectOptionOnMultipleSelectByVisibleText(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $select->selectByValue('fourth'); // text 'Fourth  with   spaces   inside'
        $select->selectByValue('fifth'); // text '   Fifth surrounded by spaces    '
        $select->selectByValue('second'); // text 'This is second option'

        $select->deselectByVisibleText('Fourth with spaces inside');
        $select->deselectByVisibleText('Fourth with spaces inside'); // should be deselected even if deselected again
        $select->deselectByVisibleText('Fifth surrounded by spaces');
        $select->deselectByVisibleText('First'); // should not select unselected option

        $this->assertContainsOptionsWithValues(['second'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfDeselectingSimpleSelectByVisibleText(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect options of a multi-select');
        $select->deselectByVisibleText('First');
    }

    /**
     * @group exclude-edge
     * https://connect.microsoft.com/IE/feedback/details/2020772/-microsoft-edge-webdriver-cannot-select-multiple-on-select-html-tag
     */
    public function testShouldDeselectOptionOnMultipleSelectByVisiblePartialText(): void
    {
        $select = $this->getWebDriverSelectForMultipleSelect();
        $select->selectByValue('fourth'); // text 'Fourth  with   spaces   inside'
        $select->selectByValue('fifth'); // text '   Fifth surrounded by spaces    '
        $select->selectByValue('second'); // text 'This is second option'
        $select->selectByValue('third'); // text 'This is not second option'
        $select->selectByValue('first'); // text 'First'
        $this->assertCount(5, $select->getAllSelectedOptions());

        $select->deselectByVisiblePartialText('second'); // should deselect two options
        $this->assertContainsOptionsWithValues(['first', 'fourth', 'fifth'], $select->getAllSelectedOptions());

        $select->deselectByVisiblePartialText('Fourth with spaces');
        $select->deselectByVisiblePartialText('Fourth with spaces'); // should be deselected even if deselected again
        $this->assertContainsOptionsWithValues(['first', 'fifth'], $select->getAllSelectedOptions());

        $select->deselectByVisiblePartialText('Fifth surrounded');
        $this->assertContainsOptionsWithValues(['first'], $select->getAllSelectedOptions());
    }

    public function testShouldThrowExceptionIfDeselectingSimpleSelectByVisiblePartialText(): void
    {
        $select = $this->getWebDriverSelectForSimpleSelect();

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect options of a multi-select');
        $select->deselectByVisiblePartialText('First');
    }

    protected function getWebDriverSelectForSimpleSelect(): WebDriverSelect
    {
        $originalElement = $this->driver->findElement(WebDriverBy::cssSelector('#select'));

        return new WebDriverSelect($originalElement);
    }

    protected function getWebDriverSelectForMultipleSelect(): WebDriverSelect
    {
        $originalElement = $this->driver->findElement(WebDriverBy::cssSelector('#select-multiple'));

        return new WebDriverSelect($originalElement);
    }

    /**
     * @param string[] $expectedValues
     */
    private function assertContainsOptionsWithValues(array $expectedValues, array $options): void
    {
        $expectedCount = count($expectedValues);
        $this->assertContainsOnlyInstancesOf(WebDriverElement::class, $options);
        $this->assertCount($expectedCount, $options);

        for ($i = 0; $i < $expectedCount; $i++) {
            $this->assertSame($expectedValues[$i], $options[$i]->getAttribute('value'));
        }
    }
}
