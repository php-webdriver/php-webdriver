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

abstract class WebDriverContainer extends WebDriverBase {
  public function element($using, $value) {
    try {
      $results = $this->curl(
        'POST',
        '/element',
        array(
          'using' => $using,
          'value' => $value));
    } catch (NoSuchElementWebDriverError $e) {
      throw new NoSuchElementWebDriverError(
        sprintf(
          'Element not found with %s, %s',
          $using,
          $value) . "\n\n" . $e->getMessage(),
        $e->getResults());
    }

    return $this->webDriverElement($results['value']);
  }

  public function elements($using, $value) {
    $results = $this->curl(
      'POST',
      '/elements',
      array(
        'using' => $using,
        'value' => $value
      ));

    return array_filter(array_map(
      array($this, 'webDriverElement'), $results['value']));
  }

  protected function webDriverElement($value) {
    return array_key_exists('ELEMENT', (array) $value) ?
      new WebDriverElement(
        $this->getElementPath($value['ELEMENT']), // url
        $value['ELEMENT']) : // id
      null;
  }


  abstract protected function getElementPath($element_id);
}
