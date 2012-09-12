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
            array("pattern" => '/^\s*rgb\(\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*\)\s*$/', "name" => "rgb %"),
            // rgb
            array("pattern" => '/^\s*rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)\s*$/', "name" => "rgb"),
            // rgba %
            array("pattern" => '/^\s*rgba\(\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(\d{1,3}|\d{1,2}\.\d+)%\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', "name" => "rgba %"),
            // rgba
            array("pattern" => '/^\s*rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', "name" => "rgba"),
            // hex
            array("pattern" => '/^#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', "name" => "hex"),
            // hex 3
            array("pattern" => '/^#([a-f0-9]{1})([a-f0-9]{1})([a-f0-9]{1})$/i', "name" => "hex 3"),
            // hsl
            array("pattern" => '/^\s*hsl\(\s*(\d{1,3})\s*,\s*(\d{1,3})\%\s*,\s*(\d{1,3})\%\s*\)\s*$/', "name" => "hsl"),
            // hsla
            array("pattern" => '/^\s*hsla\(\s*(\d{1,3})\s*,\s*(\d{1,3})\%\s*,\s*(\d{1,3})\%\s*,\s*(0|1|0\.\d+)\s*\)\s*$/', "name" => "hsla"),
        );

        $a = 1;        
        foreach($matchers as $matcher) {
            preg_match($matcher["pattern"], $color, $matches);
            if (count($matches) != 0) {
                if (count($matches) == 5) {
                    $a = $matches[4];
                }
                if ($matcher["name"] === "rgb %" || $matcher["name"] === "rgba %") {
                    // deal with percent values
                    $this->red = floor($matches[1] / 100 * 255);
                    $this->green = floor($matches[2] / 100 * 255);
                    $this->blue = floor($matches[3] / 100 * 255);
                } elseif ($matcher["name"] === "hex") {
                    // deal with hex
                    $this->red = intval($matches[1], 16);
                    $this->green = intval($matches[2], 16);
                    $this->blue = intval($matches[3], 16);
                } elseif ($matcher["name"] === "hex 3") {
                    // deal with hex 3
                    $this->red = intval($matches[1] . $matches[1], 16);
                    $this->green = intval($matches[2] . $matches[2], 16);
                    $this->blue = intval($matches[3] . $matches[3], 16);
                } elseif ($matcher["name"] === "hsl" || $matcher["name"] === "hsla") {
                    // deal with hsl madness;
                    $h = $matches[1] / 360;
                    $s = $matches[2] / 100;
                    $l = $matches[3] / 100;
                    if ($s == 0) {
                        $r = $l;
                        $g = $r;
                        $b = $r;
                    } else {
                        $hueToRgb = function($luminocity1, $luminocity2, $hue) {
                            if ($hue < 0.0) {
                                $hue += 1; 
                            }
                            if ($hue > 1.0) {
                                $hue -= 1;
                            }
                            if ($hue < 1.0 / 6.0) {
                                return ($luminocity1 + ($luminocity2 - $luminocity1) * 6.0 * $hue);
                            }
                            if ($hue < 1.0 / 2.0) {
                                return $luminocity2;
                            }
                            if ($hue < 2.0 / 3.0) {
                                return ($luminocity1 + ($luminocity2 - $luminocity1) * ((2.0 / 3.0) - $hue) * 6.0);
                            }
                            return $luminocity1;
                        };
                        $luminocity2 = ($l < 0.5) ? $l * (1 + $s) : $l + $s - $l * $s;
                        $luminocity1 = 2 * $l - $luminocity2;
                        $r = $hueToRgb($luminocity1, $luminocity2, $h + 1.0 / 3.0);
                        $g = $hueToRgb($luminocity1, $luminocity2, $h);
                        $b = $hueToRgb($luminocity1, $luminocity2, $h - 1.0 / 3.0);
                    }
                     
                    $this->red = $r * 256;
                    $this->green = $g * 256;
                    $this->blue = $b * 256;
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