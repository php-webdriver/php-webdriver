<?php
// Copyright 2004-present Facebook. All Rights Reserved.

/**
 * Managing stuff you would do in a browser.
 */
class WebDriverOptions {

  protected $executor;
  protected $sessionID;

  public function __construct($executor, $session_id) {
    $this->executor = $executor;
    $this->sessionID = $session_id;
  }

  /**
   * Return the interface for managing driver timeouts.
   *
   * @return WebDriverTimeouts
   */
  public function timeouts() {
    return new WebDriverTimeouts($this->executor, $this->sessionID);
  }

  private function execute($name, array $params = array()) {
    $command = array(
      'sessionId' => $this->sessionID,
      'name' => $name,
      'parameters' => $params,
    );
    $raw = $this->executor->execute($command);
    return $raw['value'];
  }
}
