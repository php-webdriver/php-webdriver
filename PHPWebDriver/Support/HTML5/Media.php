<?php

require_once(dirname(__FILE__) . '/../../WebDriverExceptions.php');

abstract class PHPWebDriver_WebDriver_Support_HTML5_Media {
  protected static $gettable_media_properties = array("error", "src", "current_src", "cross_origin",
                                                      "network_state", "preload", "buffered", "ready_state",
                                                      "seeking", "current_time", "initial_time", "duration",
                                                      "start_offset_time", "paused", "default_playback_rate",
                                                      "playback_rate", "played", "seekable", "ended",
                                                      "autoplay", "loop", "mediagroup", "controls", "volume",
                                                      "muted", "default_muted");
  protected static $settable_media_properties = array("src", "cross_origin", "preload", "current_time",
                                                      "default_playback_rate", "playback_rate", "autoplay",
                                                      "loop", "mediagroup", "controls", "volume", "muted",
                                                      "default_muted");
  protected static $media_errors = array("MEDIA_ERR_ABORTED",
                                         "MEDIA_ERR_NETWORK",
                                         "MEDIA_ERR_DECODE",
                                         "MEDIA_ERR_SRC_NOT_SUPPORTED");
  protected static $network_errors = array("NETWORK_EMPTY",
                                           "NETWORK_IDLE",
                                           "NETWORK_LOADING",
                                           "NETWORK_NO_SOURCE");
  public function __get($name) {
    if (in_array($name, self::$gettable_media_properties)) {
      if ($this->changed) {
        throw new PHPWebDriver_ObsoleteElementWebDriverError(sprintf(
          'DOM has changed; need to refetch it.')
          );
      }
      $value = $this->_el->session->execute(array("script" => "return arguments[0]." . $name,
                                                  "args" => array(array("ELEMENT" => $this->_el->getID())))
                                           );
      switch($name) {
        case "error":
          return $media_errors[$value];
        case "network_state":
          return $network_errors[$value];
        default:
          return $value;
      }
    } else {
      return $this->$name;
    }
  }
  
  public function __set($name, $value) {
    if (in_array($name, self::$settable_media_properties)) {
        switch($name) {
          case "current_time":
          case "default_playback_rate":
          case "playback_rate":
          case "volume":
            if (! is_double($value)) {
              throw new UnexpectedValueException(sprintf(
                '%s must be a float.',
                $name)
                );
            }
            break;
          case "src":
          case "preload":
          case "cross_origin":
          case "mediagroup":
            if (! is_string($value)) {
              throw new UnexpectedValueException(sprintf(
                '%s must be a string.',
                $name)
                );
            }
            break;
          case "autoplay":
          case "loop":
          case "controls":
          case "muted":
          case "default_muted":
            if (! is_bool($value)) {
              throw new UnexpectedValueException(sprintf(
                '%s must be a bool.',
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
    } else {
        $this->$name = $value;
    }
  }
  
  public function load() {
    $this->_el->session->execute(array("script" => "arguments[0].load()",
                                              "args" => array(array("ELEMENT" => $this->_el->getID())))
                                );
  }

  public function play() {
    $this->_el->session->execute(array("script" => "arguments[0].play()",
                                              "args" => array(array("ELEMENT" => $this->_el->getID())))
                                );
  }

  public function pause() {
    $this->_el->session->execute(array("script" => "arguments[0].pause()",
                                              "args" => array(array("ELEMENT" => $this->_el->getID())))
                                );
  }
  
  public function can_play_type($media_type) {
    return $this->_el->session->execute(array("script" => "return arguments[0].canPlayType(arguments[1])",
                                              "args" => array(array("ELEMENT" => $this->_el->getID()),
                                                              $media_type))
                                       );
  }
}