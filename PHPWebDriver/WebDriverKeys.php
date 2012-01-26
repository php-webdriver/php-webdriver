<?php

class PHPWebDriver_WebDriverKeys {
  private static $keys = array(
    'NullKey' => "\uE000",
    'CancelKey' => "\uE001",
    'HelpKey' => "\uE002",
    'BackspaceKey' => "\uE003",
    'TabKey' => "\uE004",
    'ClearKey' => "\uE005",
    'ReturnKey' => "\uE006",
    'EnterKey' => "\uE007",
    'ShiftKey' => "\uE008",
    'ControlKey' => "\uE009",
    'AltKey' => "\uE00A",
    'PauseKey' => "\uE00B",
    'EscapeKey' => "\uE00C",
    'SpaceKey' => "\uE00D",
    'PageUpKey' => "\uE00E",
    'PageDownKey' => "\uE00F",
    'EndKey' => "\uE010",
    'HomeKey' => "\uE011",
    'LeftArrowKey' => "\uE012",
    'UpArrowKey' => "\uE013",
    'RightArrowKey' => "\uE014",
    'DownArrowKey' => "\uE015",
    'InsertKey' => "\uE016",
    'DeleteKey' => "\uE017",
    'SemicolonKey' => "\uE018",
    'EqualsKey' => "\uE019",
    'Numpad0Key' => "\uE01A",
    'Numpad1Key' => "\uE01B",
    'Numpad2Key' => "\uE01C",
    'Numpad3Key' => "\uE01D",
    'Numpad4Key' => "\uE01E",
    'Numpad5Key' => "\uE01F",
    'Numpad6Key' => "\uE020",
    'Numpad7Key' => "\uE021",
    'Numpad8Key' => "\uE022",
    'Numpad9Key' => "\uE023",
    'MultiplyKey' => "\uE024",
    'AddKey' => "\uE025",
    'SeparatorKey' => "\uE026",
    'SubtractKey' => "\uE027",
    'DecimalKey' => "\uE028",
    'DivideKey' => "\uE029",
    'F1Key' => "\uE031",
    'F2Key' => "\uE032",
    'F3Key' => "\uE033",
    'F4Key' => "\uE034",
    'F5Key' => "\uE035",
    'F6Key' => "\uE036",
    'F7Key' => "\uE037",
    'F8Key' => "\uE038",
    'F9Key' => "\uE039",
    'F10Key' => "\uE03A",
    'F11Key' => "\uE03B",
    'F12Key' => "\uE03C",
    'CommandKey' => "\uE03D",
    'MetaKey' => "\uE03D",
  );
  
  public static function __callStatic($name, $arguments) {
    if (isset(self::$keys[$name])) {
      return json_decode('"' . self::$keys[$name] . '"');
    } else {
      throw new Exception("Can't type key $name");
    }
  }

}
?>