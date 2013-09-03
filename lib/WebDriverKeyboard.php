<?php


interface WebDriverKeyboard {

  public function sendKeys($keys);

  /**
   * Press a modifier key
   *
   * @see WebDriverKeys
   * @param $key
   * @return $this
   */
  public function pressKey($key);

  /**
   * Release a modifier key
   *
   * @see WebDriverKeys
   * @param $key
   * @return $this
   */
  public function releaseKey($key);

}