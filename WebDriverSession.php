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

final class WebDriverSession extends WebDriverContainer {
  private $zipArchive;
  protected function methods() {
    return array(
      'url' => 'GET', // for POST, use open($url)
      'forward' => 'POST',
      'back' => 'POST',
      'refresh' => 'POST',
      'execute' => 'POST',
      'execute_async' => 'POST',
      'screenshot' => 'GET',
      'window_handle' => 'GET',
      'window_handles' => 'GET',
      'frame' => 'POST',
      'source' => 'GET',
      'title' => 'GET',
      'keys' => 'POST',
      'orientation' => array('GET', 'POST'),
      'alert_text' => array('GET', 'POST'),
      'accept_alert' => 'POST',
      'dismiss_alert' => 'POST',
      'moveto' => 'POST',
      'click' => 'POST',
      'buttondown' => 'POST',
      'buttonup' => 'POST',
      'doubleclick' => 'POST',
      'file' => 'POST',
    );
  }

  // /session/:sessionId/url (POST)
  public function open($url) {
    $this->curl('POST', '/url', array('url' => $url));
    return $this;
  }

  // /session/:sessionId (GET)
  public function capabilities() {
    $result = $this->curl('GET', '');
    return $result['value'];
  }

  // /session/:sessionId (DELETE)
  public function close() {
    $result = $this->curl('DELETE', '');
    return $result['value'];
  }

  // /session/:sessionId/cookie (GET)
  public function getAllCookies() {
    $result = $this->curl('GET', '/cookie');
    return $result['value'];
  }

  // /session/:sessionId/cookie (POST)
  public function setCookie($cookie_json) {
    $this->curl('POST', '/cookie', array('cookie' => $cookie_json));
    return $this;
  }

  // /session/:sessionId/cookie (DELETE)
  public function deleteAllCookies() {
    $this->curl('DELETE', '/cookie');
    return $this;
  }

  // /session/:sessionId/cookie/:name (DELETE)
  public function deleteCookie($cookie_name) {
    $this->curl('DELETE', '/cookie/' . $cookie_name);
    return $this;
  }

  public function timeouts() {
    $item = new WebDriverSimpleItem($this->url . '/timeouts');
    return $item->setMethods(array(
      'async_script' => 'POST',
      'implicit_wait' => 'POST',
    ));
  }

  public function ime() {
    $item = new WebDriverSimpleItem($this->url . '/ime');
    return $item->setMethods(array(
      'available_engines' => 'GET',
      'active_engine' => 'GET',
      'activated' => 'GET',
      'deactivate' => 'POST',
      'activate' => 'POST',
    ));
  }

  // /session/:sessionId/window (DELETE)
  public function deleteWindow() {
    $this->curl('DELETE', '/window');
    return $this;
  }

  // /session/:sessionId/window (POST)
  public function focusWindow($name) {
    $this->curl('POST', '/window', array('name' => $name));
    return $this;
  }

  public function window($window_handle = 'current') {
    $item = new WebDriverSimpleItem($this->url . '/window/' . $window_handle);
    return $item->setMethods(array(
      'size' => array('GET', 'POST'),
      'position' => array('GET', 'POST'),
    ));
  }

  // /session/:sessionId/element/active (POST)
  public function activeElement() {
    $results = $this->curl('POST', '/element/active');
    return $this->webDriverElement($results['value']);
  }

  public function touch() {
    $item = new WebDriverSimpleItem($this->url . '/touch');
    return $item->setMethods(array(
      'click' => 'POST',
      'down' => 'POST',
      'up' => 'POST',
      'move' => 'POST',
      'scroll' => 'POST',
      'doubleclick' => 'POST',
      'longclick' => 'POST',
      'flick' => 'POST',
    ));
  }

  public function file($file_path) {
    $zipfile_path = $this->zipArchiveFile($file_path);
    $file = @file_get_contents($zipfile_path);

    if( $file === false ) {
      return false;
    }
    $file = base64_encode($file);

    $response = $this->curl('POST', '/file', array('file' => $file));

    if( !unlink( $zipfile_path ) )
      throw new Exception( __CLASS__ . '::' . __FUNCTION__ . " - unlink( {$zipfile_path} failed" );

    if( !isset( $response['value'] ) )
      throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' - failed. No file path returned' );

    return $response;
  }


  protected function getElementPath($element_id) {
    return sprintf('%s/element/%s', $this->url, $element_id);
  }

  private function zipArchiveFile( $file_path ) {

    // file sanity check
    $file_data = @file_get_contents($file_path);
    if( $file_data === false ) {
      throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ' - file_get_contents failed');
    }

    $file_extension = explode( '.', $file_path );
    $file_extension = array_pop( $file_extension );

    // create ZipArchive if necessary
    if ( !$this->zipArchive )
      $this->zipArchive = new ZipArchive();
    $zip = $this->zipArchive;

    // create zip archive file
    $filename_hash = sha1(time().$file_path);

    $zip_filename = sys_get_temp_dir() . "{$filename_hash}_zip.zip";
    if( $zip->open($zip_filename, ZIPARCHIVE::CREATE) === false ) {
      echo __CLASS__ . '::' . __FUNCTION__ . ' - $zip->open failed' . PHP_EOL;
      return false;
    }

    // write contents into archive
    $filename = sys_get_temp_dir() . "{$filename_hash}.{$file_extension}";
    if( @file_put_contents($filename, $file_data) === false ) {
      throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ' - file_put_contents failed');
    }
    $zip->addFile($filename, "{$filename_hash}.{$file_extension}");
    $zip->close();

    return $zip_filename;
  }
}
