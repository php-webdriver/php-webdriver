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
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Support\XPathEscaper;

/**
 * Provides helper methods for checkboxes and radio buttons.
 */
class WebDriverCheckbox implements WebDriverSelectInterface
{
    private $element;
    private $type;
    private $name;

    public function __construct(WebDriverElement $element)
    {
        $tagName = $element->getTagName();
        if ($tagName !== 'input') {
            throw new UnexpectedTagNameException('input', $tagName);
        }

        $type = $element->getAttribute('type');
        if ($type !== 'checkbox' && $type !== 'radio') {
            throw new WebDriverException('The input must be of type "checkbox" or "radio".');
        }

        $name = $element->getAttribute('name');
        if ($name === null) {
            throw new WebDriverException('The input does not have a "name" attribute.');
        }

        $this->element = $element;
        $this->type = $type;
        $this->name = $name;
    }

    public function isMultiple()
    {
        return $this->type === 'checkbox';
    }

    public function getOptions()
    {
        return $this->getRelatedElements();
    }

    public function getAllSelectedOptions()
    {
        $selectedOptions = [];
        foreach ($this->getRelatedElements() as $element) {
            if ($element->isSelected()) {
                $selectedOptions[] = $element;

                if (!$this->isMultiple()) {
                    return $selectedOptions;
                }
            }
        }

        return $selectedOptions;
    }

    public function getFirstSelectedOption()
    {
        foreach ($this->getRelatedElements() as $element) {
            if ($element->isSelected()) {
                return $element;
            }
        }

        throw new NoSuchElementException('No checkboxes are selected');
    }

    public function selectByIndex($index)
    {
        $this->byIndex($index);
    }

    public function selectByValue($value)
    {
        $this->byValue($value);
    }

    public function selectByVisibleText($text)
    {
        $this->byVisibleText($text);
    }

    public function selectByVisiblePartialText($text)
    {
        $this->byVisibleText($text, true);
    }

    public function deselectAll()
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect all options of checkboxes');
        }

        foreach ($this->getRelatedElements() as $option) {
            $this->deselectOption($option);
        }
    }

    public function deselectByIndex($index)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect checkboxes');
        }

        $this->byIndex($index, false);
    }

    public function deselectByValue($value)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect checkboxes');
        }

        $this->byValue($value, false);
    }

    public function deselectByVisibleText($text)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect checkboxes');
        }

        $this->byVisibleText($text, false, false);
    }

    public function deselectByVisiblePartialText($text)
    {
        if (!$this->isMultiple()) {
            throw new UnsupportedOperationException('You may only deselect checkboxes');
        }

        $this->byVisibleText($text, true, false);
    }

    /**
     * Selects or deselects a checkbox or a radio button by its value.
     *
     * @param string $value
     * @param bool $select
     * @throws NoSuchElementException
     */
    private function byValue($value, $select = true)
    {
        $matched = false;
        foreach ($this->getRelatedElements($value) as $element) {
            $select ? $this->selectOption($element) : $this->deselectOption($element);
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
     * Selects or deselects a checkbox or a radio button by its index.
     *
     * @param int $index
     * @param bool $select
     * @throws NoSuchElementException
     */
    private function byIndex($index, $select = true)
    {
        $options = $this->getRelatedElements();
        if (!isset($options[$index])) {
            throw new NoSuchElementException(sprintf('Cannot locate option with index: %d', $index));
        }

        $select ? $this->selectOption($options[$index]) : $this->deselectOption($options[$index]);
    }

    /**
     * Selects or deselects a checkbox or a radio button by its visible text.
     *
     * @param string $text
     * @param bool $partial
     * @param bool $select
     */
    private function byVisibleText($text, $partial = false, $select = true)
    {
        foreach ($this->getRelatedElements() as $element) {
            $normalizeFilter = sprintf(
                $partial ? 'contains(normalize-space(.), %s)' : 'normalize-space(.) = %s',
                XPathEscaper::escapeQuotes($text)
            );

            $xpath = 'ancestor::label';
            $xpathNormalize = sprintf('%s[%s]', $xpath, $normalizeFilter);

            $id = $element->getAttribute('id');
            if ($id !== null) {
                $idFilter = sprintf('@for = %s', XPathEscaper::escapeQuotes($id));

                $xpath .= sprintf(' | //label[%s]', $idFilter);
                $xpathNormalize .= sprintf(' | //label[%s and %s]', $idFilter, $normalizeFilter);
            }

            try {
                $element->findElement(WebDriverBy::xpath($xpathNormalize));
            } catch (NoSuchElementException $e) {
                if ($partial) {
                    continue;
                }

                try {
                    // Since the mechanism of getting the text in xpath is not the same as
                    // webdriver, use the expensive getText() to check if nothing is matched.
                    if ($text !== $element->findElement(WebDriverBy::xpath($xpath))->getText()) {
                        continue;
                    }
                } catch (NoSuchElementException $e) {
                    continue;
                }
            }

            $select ? $this->selectOption($element) : $this->deselectOption($element);
            if (!$this->isMultiple()) {
                return;
            }
        }
    }

    /**
     * Gets checkboxes or radio buttons with the same name.
     *
     * @param string|null $value
     * @return WebDriverElement[]
     */
    private function getRelatedElements($value = null)
    {
        $valueSelector = $value ? sprintf(' and @value = %s', XPathEscaper::escapeQuotes($value)) : '';
        $formId = $this->element->getAttribute('form');
        if ($formId === null) {
            $form = $this->element->findElement(WebDriverBy::xpath('ancestor::form'));

            $formId = $form->getAttribute('id');
            if ($formId === null) {
                return $form->findElements(WebDriverBy::xpath(
                    sprintf('.//input[@name = %s%s]', XPathEscaper::escapeQuotes($this->name), $valueSelector)
                ));
            }
        }

        return $this->element->findElements(WebDriverBy::xpath(sprintf(
            '//form[@id = %1$s]//input[@name = %2$s%3$s] | //input[@form = %1$s and @name = %2$s%3$s]',
            XPathEscaper::escapeQuotes($formId),
            XPathEscaper::escapeQuotes($this->name),
            $valueSelector
        )));
    }

    /**
     * Selects a checkbox or a radio button.
     */
    private function selectOption(WebDriverElement $option)
    {
        if (!$option->isSelected()) {
            $option->click();
        }
    }

    /**
     * Deselects a checkbox or a radio button.
     */
    private function deselectOption(WebDriverElement $option)
    {
        if ($option->isSelected()) {
            $option->click();
        }
    }
}
