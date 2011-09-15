<?php
// Copyright 2004-present Facebook. All Rights Reserved.

final class WebDriverSession extends WebDriverContainer {
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
      'window' => array('POST', 'DELETE'),
      'source' => 'GET',
      'title' => 'GET',
      'modifier' => 'POST',
      'orientation' => array('GET', 'POST'),
      'alert_text' => array('GET', 'POST'),
      'accept_alert' => 'POST',
      'dismiss_alert' => 'POST',
      'moveto' => 'POST',
      'click' => 'POST',
      'buttondown' => 'POST',
      'buttonup' => 'POST',
      'doubleclick' => 'POST',
    );
  }

  // /session/:sessionId/url (POST)
  public function open($url) {
    $this->curl('POST', '/url', array('url' => $url));
    return $this;
  }

  // /session/:sessionId (GET)
  public function capabilities() {
    return $this->curl('GET', '');
  }

  // /session/:sessionId (DELETE)
  public function close() {
    return $this->curl('DELETE', '');
  }

  // /session/:sessionId/cookie (GET)
  public function getAllCookies() {
    return $this->curl('GET', '/cookie');
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
}
