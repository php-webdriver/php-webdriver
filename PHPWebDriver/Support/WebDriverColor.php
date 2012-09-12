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
            array('/^\s*rgb\(\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*\)\s*$/', true),
            // rgb
            array('/^\s*rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)\s*$/', false),
            // rgba %
            array('/^\s*rgba\(\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', true),
            // rgba
            array('/^\s*rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', false),
        );

        $a = 1;        
        foreach($matchers as $matcher) {
            preg_match($matcher[0], $color, $matches);
            if (count($matches) != 0) {
                if (count($matches) == 5) {
                    $a = $matches[4];
                }
                $this->red = $matches[1];
                $this->green = $matches[2];
                $this->blue = $matches[3];
                $this->alpha = $a;
                $this->percent = $matcher[1];
            }
        }
        
        return $this;
    }

    public function rgb() {
        if ($this->percent) {
            return 'rgb(' . floor($this->red / 100 * 255) . ', ' . floor($this->green / 100 * 255) . ', ' . floor($this->blue / 100 * 255) . ')';
        } else {
            return 'rgb(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ')';
        }
    }
        
    public function rgba() {
        if ($this->percent) {
            return 'rgba(' . floor($this->red / 100 * 255) . ', ' . floor($this->green / 100 * 255) . ', ' . floor($this->blue / 100 * 255) . ', ' . $this->alpha . ')';
        } else {
            return 'rgba(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ', ' . $this->alpha . ')';
        }
    }
        
    public function hex() {}
        
}