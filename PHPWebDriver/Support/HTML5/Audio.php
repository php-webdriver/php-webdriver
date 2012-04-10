<?php

require_once('Media.php');

class PHPWebDriver_WebDriver_Support_HTML5_Audio extends PHPWebDriver_WebDriver_Support_HTML5_Media {
  /**
   * Constructor. A check is made that the given element is, indeed, a Audio tag. If it is not,
   * then an UnexpectedTagNameException is thrown.
   * 
   * @param string $webelement - element Audio element to wrap
   * 
   *   Example:
   *       $a = new PHPWebDriver_WebDriver_Support_HTML5_Audio($session->element("tag_name", "audio"));
   *       $a->play();
   */
  public function __construct($webelement) {
    if (strtolower($webelement->name()) != "audio") {
      throw new PHPWebDriver_UnexpectedTagNameException(sprintf(
        'Audio only works on <audio> elements, not on <%s>.',
        $webelement->name)
        );
    }
    $this->_el = $webelement;
    $this->changed = False;
  }
}