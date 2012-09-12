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
        $this->_color = $color;
        

        if (substr($color, 0, 3) === "rgb" && substr($color, 3, 4) !== "a") {
            // rgb
            $pattern = '/^\s*rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)\s*$/';
        } elseif (substr($color, 0, 4) === "rgba") {
            // rgba
            $pattern = '/^\s*rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0\.\d+)\s*\)\s*$/';
        }
        
        preg_match($pattern, $color, $matches);
        $a = 1;
        if (count($matches) != 0) {
            if (count($matches) == 5) {
                $a = $matches[4];
            }
            $this->red = $matches[1];
            $this->green = $matches[2];
            $this->blue = $matches[3];
            $this->alpha = $a;
        } else {
            throw new InvalidArgumentException('Did not know how to convert ' . $color . ' into a color');
        }
        return $this;
    }

    public function rgb() {
        return 'rgb(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ')';
    }
        
    public function rgba() {
        return 'rgba(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ', ' . $this->alpha . ')';
    }
        
    public function hex() {}
        
}