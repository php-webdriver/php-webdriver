<?php

require_once('Media.php');

class PHPWebDriver_WebDriver_Support_HTML5_Video extends PHPWebDriver_WebDriver_Support_HTML5_Media {
  protected $gettable_video_properties = array("width", "height", "videoWidth", "videoHeight", "poster");
  protected $settable_video_properties = array("width", "height", "poster");  

  /**
   * Constructor. A check is made that the given element is, indeed, a Video tag. If it is not,
   * then an UnexpectedTagNameException is thrown.
   * 
   * @param string $webelement - element Audio element to wrap
   * 
   *   Example:
   *       $a = new PHPWebDriver_WebDriver_Support_HTML5_Video($session->element("tag_name", "video"));
   *       $a->play();
   */
  public function __construct($webelement) {
    if (strtolower($webelement->name()) != "video") {
      throw new PHPWebDriver_UnexpectedTagNameException(sprintf(
        'Video only works on <video> elements, not on <%s>.',
        $webelement->name)
        );
    }
    $this->_el = $webelement;
    $this->changed = False;
  }
  
  public function __get($name) {
    if (in_array($name, $this->gettable_video_properties)) {
      if ($this->changed) {
        throw new PHPWebDriver_ObsoleteElementWebDriverError(sprintf(
          'DOM has changed; need to refetch it.')
          );
      }
      return $this->_el->session->execute(array("script" => "return arguments[0]." . $name,
                                                "args" => array(array("ELEMENT" => $this->_el->getID())))
                                         );
    } elseif (in_array($name, parent::$gettable_media_properties)) {
      return parent::__get($name);
    } else {
      return $this->$name;
    }
  }
  
  public function __set($name, $value) {
    if (in_array($name, $this->settable_video_properties)) {
        switch($name) {
          case "width":
          case "height":
            if (! is_long($value)) {
              throw new UnexpectedValueException(sprintf(
                '%s must be a long.',
                $name)
                );
            }
            break;
          case "poster":
            if (! is_string($value)) {
              throw new UnexpectedValueException(sprintf(
                '%s must be a string.',
                $name)
                );
            }
            break;
        }
        $this->_el->session->execute(array("script" => "arguments[0]." . $name . " = arguments[1]",
                                            "args" => array(array("ELEMENT" => $this->_el->getID()),
                                                            $value))
                                    );
        $this->changed = True;
    } elseif (in_array($name, parent::$settable_media_properties)) {
      return parent::__set($name, $value);
    } else {
        $this->$name = $value;
    }
  }
}