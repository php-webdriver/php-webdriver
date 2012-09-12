<?php
// Copyright 2012-present Element 34
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

class PHPWebDriver_Support_Color {
    public function __construct($color) {
        $matchers = array(
            // rgb %
            array('/^\s*rgb\(\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*\)\s*$/', true, false, false),
            // rgb
            array('/^\s*rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)\s*$/', false, false, false),
            // rgba %
            array('/^\s*rgba\(\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', true, false, false),
            // rgba
            array('/^\s*rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', false, false, false),
            // hex
            array('/^#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', false, true, false),
            // hex3
            array('/^#([a-f0-9]{1})([a-f0-9]{1})([a-f0-9]{1})$/i', false, true, true),
        );

        $a = 1;        
        foreach($matchers as $matcher) {
            preg_match($matcher[0], $color, $matches);
            if (count($matches) != 0) {
                if (count($matches) == 5) {
                    $a = $matches[4];
                }
                if ($matcher[1]) {
                    // deal with percent values
                    $this->red = floor($matches[1] / 100 * 255);
                    $this->green = floor($matches[2] / 100 * 255);
                    $this->blue = floor($matches[3] / 100 * 255);
                } elseif ($matcher[2] && !$matcher[3]) {
                    // deal with hex
                    $this->red = intval($matches[1], 16);
                    $this->green = intval($matches[2], 16);
                    $this->blue = intval($matches[3], 16);
                } elseif ($matcher[2] && $matcher[3]) {
                    // deal with hex 3
                    $this->red = intval($matches[1] . $matches[1], 16);
                    $this->green = intval($matches[2] . $matches[2], 16);
                    $this->blue = intval($matches[3] . $matches[3], 16);
                } else {
                    // regular things
                    $this->red = $matches[1];
                    $this->green = $matches[2];
                    $this->blue = $matches[3];
                }
                $this->alpha = $a;
            }
        }
        
        return $this;
    }

    public function rgb() {
        return 'rgb(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ')';
    }
        
    public function rgba() {
        return 'rgba(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ', ' . $this->alpha . ')';
    }
        
    public function hex() {
        return '#' . sprintf('%02x', $this->red) . sprintf('%02x', $this->green) . sprintf('%02x', $this->blue);
    }
        
}