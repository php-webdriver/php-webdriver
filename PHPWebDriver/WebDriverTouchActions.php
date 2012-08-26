<?php
// Copyright 2011-present Element 34
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

include_once('WebDriverActionChains.php');

class PHPWebDriver_WebDriverTouchActions extends PHPWebDriver_WebDriverActionChains {
    
    public function __construct($session) {
        parent::__construct($session);
    }
    
    public function single_tap($element, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->click(array('element' => '" . $element->getID() . "'), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }

    public function down($x, $y, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->down(array('x' => " . $x . ", 'y' => " . $y . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }
    
    public function move($x, $y, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->move(array('x' => " . $x . ", 'y' => " . $y . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }
    
    public function up($x, $y, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->up(array('x' => " . $x . ", 'y' => " . $y . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }

    public function element_scroll($element, $xoffset, $yoffset, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->scroll(array('element' => '" . $element->getID() . "', 'xoffset' => " . $xoffset . ", 'yoffset' => " . $yoffset . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }
    
    public function scroll($xoffset, $yoffset, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->scroll(array('xoffset' => " . $xoffset . ", 'yoffset' => " . $yoffset . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;        
    }
    
    public function double_tap($element, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->doubleclick(array('element' => '" . $element->getID() . "'), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }

    public function long_tap($element, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->longclick(array('element' => '" . $element->getID() . "'), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }

    public function flick($xspeed, $yspeed, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->flick(array('xspeed' => " . $xspeed . ", 'yspeed' => " . $yspeed . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }

    public function element_flick($element, $xoffset, $yoffset, $speed, $curl_opts = array()) {
        $this->actions[] = "return \$this->session->touch()->flick(array('element' => '" . $element->getID() . "', 'xoffset' => " . $xoffset . ", 'yoffset' => " . $yoffset . ", 'speed' => " . $speed . "), " . unwind_associated_array($curl_opts) .  ");";
        return $this;
    }
}

