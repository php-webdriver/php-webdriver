<?php


class RemoteKeyboard implements WebDriverKeyboard {

  private $executor;

  protected $modifiers = array('control','shift','alt','command','meta');

  public function __construct($executor) {
    $this->executor = $executor;
  }

  /**
   * Send keys to active element
   *
   * @param $keys
   * @return $this
   */
  public function sendKeys($keys) {
    $this->sendKeysToActiveElement(WebDriverKeys::encode($keys));
    return $this;
  }

  /**
   * Press a modifier key
   *
   * @see WebDriverKeys
   * @param $key
   * @return $this
   */
  public function press($key)
  {
    $this->ensureModifier($key);
    $this->sendKeysToActiveElement(WebDriverKeys::encode(array($key)));
    return $this;
  }

  /**
   * Release a modifier key
   *
   * @see WebDriverKeys
   * @param $key
   * @return $this
   */
  public function release($key)
  {
    $this->ensureModifier($key);
    $this->sendKeysToActiveElement(WebDriverKeys::encode(array($key)));
    return $this;
  }

  private function sendKeysToActiveElement($value)
  {
    $params = array(
      'value' => $value
    );
    $this->executor->execute('sendKeysToActiveElement', $params);   
  }    

  private function ensureModifier($key)
  {
    if (!in_array($key, $this->modifiers))
      throw new InvalidArgumentException("$key is not a modifier key, expected one of ".implode(', ',$this->modifiers));
  }
}