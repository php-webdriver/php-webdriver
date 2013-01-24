<?php
// Copyright 2013-present Element 34
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

require_once(dirname(__FILE__) . '/../WebDriverBy.php');
require_once(dirname(__FILE__) . '/../WebDriverExceptions.php');

class PHPWebDriver_Support_WebDriverSelect {
    public function __construct($element) {
        $name = $element->name();
        if (strtolower($name) != 'select') {
            throw new PHPWebDriver_UnexpectedTagNameException("Select only works on <select> elements not <$name>");
        }

        $this->_element = $element;
        $multi = $this->_element->attribute('multiple');
        if ($multi && $multi != 'false') {
            $this->is_multiple = true;
        } else {
            $this->is_multiple = false;
        }
    }

    function __get($property) {
        switch($property) {
            case 'options':
                return $this->_element->elements(PHPWebDriver_WebDriverBy::TAG_NAME, 'option');
            case 'all_selected_options':
                $options = array();
                foreach ($this->options as $option) {
                    if ($option->selected()) {
                        $options[] = $option;
                    }
                }
                return $options;
            case 'first_selected_value':
                foreach ($this->options as $option) {
                    if ($option->selected()) {
                        return $option;
                    }
                }
                throw new PHPWebDriver_NoSuchElementWebDriverError("No options were selected");
            default:
              return $this->$property;
        }
    }

    function select_by_value($value) {
        $css = 'option[value=' . $this->escape_string($value) . ']';
        $opts = $this->_element->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, $css);
        $matched = False;
        foreach ($opts as $opt) {
            $this->set_selected($opt);
            if (! $this->is_multiple) {
                return;
            }
            $matched = True;
        }
        if (! $matched) {
            throw new PHPWebDriver_NoSuchElementWebDriverError("Cannot locate option with value: $value");
        }
    }

    function select_by_index($index) {
        $match = (string)$index;
        foreach ($this->options as $option) {
            if ($option->attribute('index') == $match) {
                $this->set_selected($option);
                if (! $this->is_multiple) {
                    return;
                }
                $match = True;
            }
        }
        if ($match == (string)$index) {
            throw new PHPWebDriver_NoSuchElementWebDriverError("Cannot locate option with index: $index");
        }
    }

    function select_by_visible_text($text) {
        $xpath = ".//option[normalize-space(.) = " . $this->escape_string($text) . ']';
        $opts = $this->_element->elements(PHPWebDriver_WebDriverBy::XPATH, $xpath);
        $matched = False;
        foreach ($opts as $opt) {
            $this->set_selected($opt);
            if (! $this->is_multiple) {
                return;
            }
            $matched = True;
        }

        if (count($opts) == 0 && strstr($text, " ")) {
            $sub_string_without_space = $this->get_longest_token($text);
            if ($sub_string_without_space == "") {
                $candidates = $this->options;
            } else {
                $xpath = ".//option[contains(.," . $this->escape_string($sub_string_without_space) . ")]";
                $candidates = $this->_element->elements(PHPWebDriver_WebDriverBy::XPATH, $xpath);
            }
            foreach ($candidates as $candidate) {
                $this->set_selected($candidate);
                if (! $this->is_multiple) {
                    return;
                }
                $matched = True;
            }
        }

        if (! $matched) {
            throw new PHPWebDriver_NoSuchElementWebDriverError("Cannot locate option with visible text: $text");
        }
    }

    function deselect_all() {
        if (! $this->is_multiple) {
            throw new PHPWebDriver_NotImplementedError("You may only deselect all options of a multi-select");
        }
        foreach ($this->options as $option) {
            $this->unset_selected($option);
        }
    }

    function deselect_by_value($value) {
        if (! $this->is_multiple) {
            throw new PHPWebDriver_NotImplementedError("You may only deselect options of a multi-select");
        }
        $css = "option[value = " .  $this->escape_string($value) . "]";
        $opts = $this->_element->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, $css);
        foreach ($opts as $opt) {
            $this->unset_selected($opt);
        }
    }

    function deselect_by_index($index) {
        if (! $this->is_multiple) {
            throw new PHPWebDriver_NotImplementedError("You may only deselect options of a multi-select");
        }
        $match = (string)$index;
        foreach ($this->options as $option) {
            if ($option->attribute('index') == $match) {
                $this->unset_selected($option);
            }
        }
    }

    function deselect_by_visible_text($text) {
        if (! $this->is_multiple) {
            throw new PHPWebDriver_NotImplementedError("You may only deselect options of a multi-select");
        }
        $xpath = ".//option[normalize-space(.) = " . $this->escape_string($text) . ']';
        $opts = $this->_element->elements(PHPWebDriver_WebDriverBy::XPATH, $xpath);
        foreach ($opts as $opt) {
            $this->unset_selected($opt);
        }
    }

    private function set_selected($option) {
        if (! $option->selected()) {
            $option->click();
        }
    }

    private function unset_selected($option) {
        if ($option->selected()) {
            $option->click();
        }
    }

    private function escape_string($value) {
        if (strstr($value, '"') && strstr($value, "'")) {
            $substrings = $str_split($value, '"');
            $result = array('concat(');
            foreach($substrings as $substring) {
                array_push($result, "\"$substring\"");
                array_push($result, ", '\"', ");
            }
            unset($result[(count($result)-1)]);

            $last = $result[(count($result)-1)];
            if (substr($last, strlen($last) - 1 ) == '"') {
                array_push($result, ", '\"', ");
            }
            return implode($results) . ")";
        }

        if (strstr($value, '"')) {
            return "'$value'";
        }
        return "\"$value\"";
    }

    private function get_longest_token($value) {
        $items = explode(" ", $value);
        $longest = "";
        foreach ($items as $item) {
            if (strlen($item) > strlen($longest)) {
                $longest = $item;
            }
        }
        return $longest;
    }

}
?>