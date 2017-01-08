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
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\Exception\UnsupportedOperationException;
use Facebook\WebDriver\Support\XPathEscaper;

/**
 * Models a SELECT tag, providing helper methods to select and deselect options.
 */
class WebDriverSelect
{
    private $element;
    private $isMulti;

    public function __construct(WebDriverElement $element)
    {
        $tag_name = $element->getTagName();

        if ($tag_name !== 'select') {
            throw new UnexpectedTagNameException('select', $tag_name);
        }
        $this->element = $element;
        $value = $element->getAttribute('multiple');
        $this->isMulti = ($value === 'true');
    }

    /**
     * @return bool Whether this select element support selecting multiple options.
     */
    public function isMultiple()
    {
        return $this->isMulti;
    }

    /**
     * @return WebDriverElement[] All options belonging to this select tag.
     */
    public function getOptions()
    {
        return $this->element->findElements(WebDriverBy::tagName('option'));
    }

    /**
     * @return WebDriverElement[] All selected options belonging to this select tag.
     */
    public function getAllSelectedOptions()
    {
        $selected_options = [];
        foreach ($this->getOptions() as $option) {
            if ($option->isSelected()) {
                $selected_options[] = $option;
            }
        }

        return $selected_options;
    }

    /**
     * @throws NoSuchElementException
     *
     * @return WebDriverElement The first selected option in this select tag (or
     *                          the currently selected option in a normal select)
     */
    public function getFirstSelectedOption()
    {
        foreach ($this->getOptions() as $option) {
            if ($option->isSelected()) {
                return $option;
            }
        }

        throw new NoSuchElementException('No options are selected');
    }

    /**
     * Select the option at the given index.
     *
     * @param int $index The index of the option. (0-based)
     *
     * @throws NoSuchElementException
     */
    public function selectByIndex($index)
    {
        foreach ($this->getOptions() as $option) {
            if ($option->getAttribute('index') === (string) $index) {
                $this->selectOption($option);

                return;
            }
        }

        throw new NoSuchElementException(sprintf('Cannot locate option with index: %d', $index));
    }

    /**
     * Select all options that have value attribute matching the argument. That
     * is, when given "foo" this would select an option like:
     *
     * <option value="foo">Bar</option>;
     *
     * @param string $value The value to match against.
     *
     * @throws NoSuchElementException
     */
    public function selectByValue($value)
    {
        $matched = false;
        $xpath = './/option[@value = ' . XPathEscaper::escapeQuotes($value) . ']';
        $options = $this->element->findElements(WebDriverBy::xpath($xpath));

        foreach ($options as $option) {
            $this->selectOption($option);
            if (!$this->isMultiple()) {
                return;
            }
            $matched = true;
        }

        if (!$matched) {
            throw new NoSuchElementException(
                sprintf('Cannot locate option with value: %s', $value)
            );
        }
    }

    /**
     * Select all options that display text matching the argument. That is, when
     * given "Bar" this would select an option like:
     *
     * <option value="foo">Bar</option>;
     *
     * @param string $text The visible text to match against.
     *
     * @throws NoSuchElementException
     */
    public function selectByVisibleText($text)
    {
        $matched = false;
        $xpath = './/option[normalize-space(.) = ' . XPathEscaper::escapeQuotes($text) . ']';
        $options = $this->element->findElements(WebDriverBy::xpath($xpath));

        foreach ($options as $option) {
            $this->selectOption($option);
            if (!$this->isMultiple()) {
                return;
            }
            $matched = true;
        }

        // Since the mechanism of getting the text in xpath is not the same as
        // webdriver, use the expensive getText() to check if nothing is matched.
        if (!$matched) {
            foreach ($this->getOptions() as $option) {
                if ($option->getText() === $text) {
                    $this->selectOption($option);
                    if (!$this->isMultiple()) {
                        return;
                    }
                    $matched = true;
                }
            }
        }

        if (!$matched) {
            throw new NoSuchElementException(
                sprintf('Cannot locate option with text: %s', $text)
            );
        }
    }

    /**
     * Select all options that display text partially matching the argument. That is, when
     * given "Bar" this would select an option like:
     *
     * <option value="bar">Foo Bar Baz</option>;
     *
     * @param string $text The visible text to match against.
     *
     * @throws NoSuchElementException
     */
    public function selectByVisiblePartialText($text)
    {
        $matched = false;
        $xpath = './/option[contains(normalize-space(.), ' . XPathEscaper::escapeQuotes($text) . ')]';
        $options = $this->element->findElements(WebDriverBy::xpath($xpath));

        foreach ($options as $option) {
            $this->selectOption($option);
            if (!$this->isMultiple()) {
                return;
            }
            $matched = true;
        }

        if (!$matched) {
            throw new NoSuchElementException(
                sprintf('Cannot locate option with text: %s', $text)
            );
        }
    }

    /**
     * Deselect all options in multiple select tag.
     *
     * @throws UnsupportedOperationException If the SELECT does not support multiple selections
     */
    public function deselectAll()
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect all options of a multi-select');
        }

        foreach ($this->getOptions() as $option) {
            $this->deselectOption($option);
        }
    }

    /**
     * Deselect the option at the given index.
     *
     * @param int $index The index of the option. (0-based)
     * @throws UnsupportedOperationException If the SELECT does not support multiple selections
     */
    public function deselectByIndex($index)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect options of a multi-select');
        }

        foreach ($this->getOptions() as $option) {
            if ($option->getAttribute('index') === (string) $index) {
                $this->deselectOption($option);

                return;
            }
        }
    }

    /**
     * Deselect all options that have value attribute matching the argument. That
     * is, when given "foo" this would deselect an option like:
     *
     * <option value="foo">Bar</option>;
     *
     * @param string $value The value to match against.
     * @throws UnsupportedOperationException If the SELECT does not support multiple selections
     */
    public function deselectByValue($value)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect options of a multi-select');
        }

        $xpath = './/option[@value = ' . XPathEscaper::escapeQuotes($value) . ']';
        $options = $this->element->findElements(WebDriverBy::xpath($xpath));
        foreach ($options as $option) {
            $this->deselectOption($option);
        }
    }

    /**
     * Deselect all options that display text matching the argument. That is, when
     * given "Bar" this would deselect an option like:
     *
     * <option value="foo">Bar</option>;
     *
     * @param string $text The visible text to match against.
     * @throws UnsupportedOperationException If the SELECT does not support multiple selections
     */
    public function deselectByVisibleText($text)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect options of a multi-select');
        }

        $xpath = './/option[normalize-space(.) = ' . XPathEscaper::escapeQuotes($text) . ']';
        $options = $this->element->findElements(WebDriverBy::xpath($xpath));
        foreach ($options as $option) {
            $this->deselectOption($option);
        }
    }

    /**
     * Deselect all options that display text matching the argument. That is, when
     * given "Bar" this would deselect an option like:
     *
     * <option value="foo">Foo Bar Baz</option>;
     *
     * @param string $text The visible text to match against.
     * @throws UnsupportedOperationException If the SELECT does not support multiple selections
     */
    public function deselectByVisiblePartialText($text)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect options of a multi-select');
        }

        $xpath = './/option[contains(normalize-space(.), ' . XPathEscaper::escapeQuotes($text) . ')]';
        $options = $this->element->findElements(WebDriverBy::xpath($xpath));
        foreach ($options as $option) {
            $this->deselectOption($option);
        }
    }

    /**
     * Mark option selected
     * @param WebDriverElement $option
     */
    protected function selectOption(WebDriverElement $option)
    {
        if (!$option->isSelected()) {
            $option->click();
        }
    }

    /**
     * Mark option not selected
     * @param WebDriverElement $option
     */
    protected function deselectOption(WebDriverElement $option)
    {
        if ($option->isSelected()) {
            $option->click();
        }
    }
}
