<?php
/**
 * Class WebContainer
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 24.06.13
 */

abstract class WebContainer
{

  /** @var \WebDriverCommandExecutor */
  protected $executor;

  /** @var string */
  protected $sessionID;

  /**
   * @param WebDriverCommandExecutor $executor
   * @param $sessionID
   */
  public function __construct(WebDriverCommandExecutor $executor, $sessionID)
  {
    $this->executor = $executor;
    $this->sessionID = (string)$sessionID;
  }

  /**
   * Find the first WebDriverElement using the given mechanism.
   *
   * @param WebDriverBy $by
   * @return WebDriverElement NoSuchElementWebDriverError is thrown in
   *      WebDriverCommandExecutor if no element is found.
   * @see WebDriverBy
   */
  abstract public function findElement(WebDriverBy $by);

  /**
   * Find all WebDriverElements within the current page using the given
   * mechanism.
   *
   * @param WebDriverBy $by
   * @return array A list of all WebDriverElements, or an empty array if
   *      nothing matches
   * @see WebDriverBy
   */
  abstract public function findElements(WebDriverBy $by);

  /**
   * Find element and return his existence
   *
   * @param WebDriverBy $by
   * @return bool Element existence
   * @see WebDriverBy
   */
  public function hasElement(WebDriverBy $by)
  {
    try {
      $this->findElement($by);
      return true;
    } catch (NoSuchElementWebDriverError $ex) {
    }

    return false;
  }

  /**
   * Return the WebDriverElement with the given id.
   *
   * @param string $id The id of the element to be created.
   * @return WebDriverElement
   */
  protected function newElement($id)
  {
    return new WebDriverElement($this->executor, $this->sessionID, $id);
  }

  /**
   * @param       $name
   * @param array $params
   * @return array
   */
  abstract protected function execute($name, array $params = array());
}