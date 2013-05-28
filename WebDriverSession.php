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
  private $_zipArchive;
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
      'file' => 'POST'
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

  protected function getElementPath($element_id) {
    return sprintf('%s/element/%s', $this->url, $element_id);
  }

  /**
   * Undocumented JSONWireProtocol :sessionId/file
   *
   * @param   string $file_path   FQ path to file to upload to RC
   */
  public function file( $file_path ) {

    $zipfile_path = $this->_zipArchiveFile( $file_path );
    $file         = @file_get_contents( $zipfile_path );

    if( $file === false ) {

      throw new Exception( "Unable to read generated zip file: {$zipfile_path}" );

    } // if !file

    $file     = base64_encode( $file );
    $json     = array( 'file' => $file );
    $response = $this->curl( 'POST', '/file', $json );

    if( !unlink( $zipfile_path ) ) {

      throw new Exception( __CLASS__ . '::' . __FUNCTION__ . " - unlink( {$zipfile_path} failed" );

    } // if !unlink

    return $response;

  } // file

  /**
   * Creates a zip archive with the given file
   *
   * @param   string $file_path   FQ path to file
   * @return  string              Generated zip file
   */
  protected function _zipArchiveFile( $file_path ) {

    // file MUST be readable
    if( !is_readable( $file_path ) ) {

      throw new Exception( "Unable to read {$file_path}" );

    } // if !file_data

    $filename_hash  = sha1( time().$file_path );
    $tmp_dir        = $this->_getTmpDir();
    $zip_filename   = "{$tmp_dir}{$filename_hash}.zip";
    $zip            = $this->_getZipArchiver();

    if( $zip->open( $zip_filename, ZIPARCHIVE::CREATE ) === false ) {

      throw new Exception( "Unable to create zip archive: {$zip_filename}" );

    } // if !open

    $zip->addFile( $file_path, basename( $file_path ) );
    $zip->close();

    return $zip_filename;

  } // _zipArchiveFile

  /**
   * Returns a runtime instance of a ZipArchive
   *
   * @return ZipArchive
   */
  protected function _getZipArchiver() {

    // create ZipArchive if necessary
    if ( !$this->_zipArchive ) {

      $this->_zipArchive = new ZipArchive();

    } // if !zipArchive

    return $this->_zipArchive;

  } // _getZipArchiver

  /**
   * Calls sys_get_temp_dir and ensures that it has a trailing slash
   * ( behavior varies across systems )
   *
   * @return string
   */
  protected function _getTmpDir() {

    return rtrim( sys_get_temp_dir(), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

  } // _getTmpDir


}
