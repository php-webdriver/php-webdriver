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

require_once('WebDriverElement.php');

abstract class PHPWebDriver_WebDriverContainer extends PHPWebDriver_WebDriverBase {
  public function element($using, $value, $curl_opts = array()) {
    try {
      $results = $this->curl(
        'POST',
        '/element',
        array(
          'using' => $using,
          'value' => $value),
        $curl_opts);
    } catch (PHPWebDriver_NoSuchElementWebDriverError $e) {
      throw new PHPWebDriver_NoSuchElementWebDriverError(
        sprintf(
          'Element not found with %s, %s',
          $using,
          $value) . "\n\n" . $e->getMessage(),
          $e->getResults());
    }
    return $this->webDriverElement($results['value']);
  }

  public function elements($using, $value, $curl_opts = array()) {
    $results = $this->curl(
      'POST',
      '/elements',
      array(
        'using' => $using,
        'value' => $value)
      , $curl_opts);

    return array_filter(array_map(
      array($this, 'webDriverElement'), $results['value']));
  }

  protected function webDriverElement($value) {
    if (array_key_exists('ELEMENT', (array) $value)) {
      return new PHPWebDriver_WebDriverElement(
            $this->getElementPath($value['ELEMENT']), // url
            $value['ELEMENT'],                        // id
            $this                                     // session
          );
    }
    return null;
  }

  abstract protected function getElementPath($element_id);
}
