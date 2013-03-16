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

final class PHPWebDriver_WebDriverElement extends PHPWebDriver_WebDriverContainer {
  protected function methods() {
    return array(
      'active' => 'POST',
      'click' => 'POST',
      'submit' => 'POST',
      'text' => 'GET',
      'value' => 'POST',
      'name' => 'GET',
      'clear' => 'POST',
      'selected' => 'GET',
      'enabled' => 'GET',
      'attribute' => 'GET',
      'equals' => 'GET',
      'displayed' => 'GET',
      'location' => 'GET',
      'location_in_view' => 'GET',
      'size' => 'GET',
      'css' => 'GET',
    );
  }

  private $id;
  public function __construct($url, $id, $session) {
    $this->id = $id;
    parent::__construct($url);
    $this->session = $session;
  }

  public function getID() {
    return $this->id;
  }

  protected function getElementPath($element_id) {
    return preg_replace(sprintf('/%s$/', $this->id), $element_id, $this->url);
  }
  
  public function sendKeys($keys) {
    if (file_exists($keys)) {
      // get a new random name for our zip -- why php://memory doesn't work is a mystery and a pain
      // ok, i know why, but definitly a pain
      $filename = tempnam(sys_get_temp_dir(), 'wd-');

      // make a zip of the file we want to send to the remote server
      $zip = new ZipArchive();
      $code_open = $zip->open($filename, ZIPARCHIVE::OVERWRITE);
      if ($code_open === True) {
        // without the basename() it adds the paths in the zip which doesn't work so well
        $code_add = $zip->addFile($keys, basename($keys));
        $zip->close();
      }

      // base64 the zip
      $contents = fread(fopen($filename, 'r+b'), filesize($filename));
      $encoded = base64_encode($contents);

      // the response from the upload is the path on the remote server
      $upload_result = $this->session->file(array('file' => $encoded));

      // so act like it was always thus
      $keys = $upload_result;

      // oh, and cleanup
      unlink($filename);
    }
    $results = $this->value(array("value" => array($keys)));
    return $results;
  }
}
