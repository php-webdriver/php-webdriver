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

final class WebDriverCurlException extends Exception {} // When curls fail

abstract class WebDriverException extends Exception {

  private $results;

  public function __construct($message, $results = null) {
    parent::__construct($message);
    $this->results = $results;
  }

  public function getResults() {
    return $this->results;
  }

  public static function throwException($status_code, $message, $results) {
    switch ($status_code) {
      case 0:
        // Success
        break;
      case 1:
        throw new IndexOutOfBoundsWebDriverError($message, $results);
      case 2:
        throw new NoCollectionWebDriverError($message, $results);
      case 3:
        throw new NoStringWebDriverError($message, $results);
      case 4:
        throw new NoStringLengthWebDriverError($message, $results);
      case 5:
        throw new NoStringWrapperWebDriverError($message, $results);
      case 6:
        throw new NoSuchDriverWebDriverError($message, $results);
      case 7:
        throw new NoSuchElementWebDriverError($message, $results);
      case 8:
        throw new NoSuchFrameWebDriverError($message, $results);
      case 9:
        throw new UnknownCommandWebDriverError($message, $results);
      case 10:
        throw new ObsoleteElementWebDriverError($message, $results);
      case 11:
        throw new ElementNotDisplayedWebDriverError($message, $results);
      case 12:
        throw new InvalidElementStateWebDriverError($message, $results);
      case 13:
        throw new UnhandledWebDriverError($message, $results);
      case 14:
        throw new ExpectedWebDriverError($message, $results);
      case 15:
        throw new ElementNotSelectableWebDriverError($message, $results);
      case 16:
        throw new NoSuchDocumentWebDriverError($message, $results);
      case 17:
        throw new UnexpectedJavascriptWebDriverError($message, $results);
      case 18:
        throw new NoScriptResultWebDriverError($message, $results);
      case 19:
        throw new XPathLookupWebDriverError($message, $results);
      case 20:
        throw new NoSuchCollectionWebDriverError($message, $results);
      case 21:
        throw new TimeOutWebDriverError($message, $results);
      case 22:
        throw new NullPointerWebDriverError($message, $results);
      case 23:
        throw new NoSuchWindowWebDriverError($message, $results);
      case 24:
        throw new InvalidCookieDomainWebDriverError($message, $results);
      case 25:
        throw new UnableToSetCookieWebDriverError($message, $results);
      case 26:
        throw new UnexpectedAlertOpenWebDriverError($message, $results);
      case 27:
        throw new NoAlertOpenWebDriverError($message, $results);
      case 28:
        throw new ScriptTimeoutWebDriverError($message, $results);
      case 29:
        throw new InvalidElementCoordinatesWebDriverError($message, $results);
      case 30:
        throw new IMENotAvailableWebDriverError($message, $results);
      case 31:
        throw new IMEEngineActivationFailedWebDriverError($message, $results);
      case 32:
        throw new InvalidSelectorWebDriverError($message, $results);
      case 33:
        throw new SessionNotCreatedWebDriverError($message, $results);
      case 34:
        throw new MoveTargetOutOfBoundsWebDriverError($message, $results);
      default:
        throw new UnrecognizedWebDriverErrorWebDriverError($message, $results);
    }
  }
}

class IndexOutOfBoundsWebDriverError extends WebDriverException {} // 1
class NoCollectionWebDriverError extends WebDriverException {} // 2
class NoStringWebDriverError extends WebDriverException {} // 3
class NoStringLengthWebDriverError extends WebDriverException {} // 4
class NoStringWrapperWebDriverError extends WebDriverException {} // 5
class NoSuchDriverWebDriverError extends WebDriverException {} // 6
class NoSuchElementWebDriverError extends WebDriverException {} // 7
class NoSuchFrameWebDriverError extends WebDriverException {} // 8
class UnknownCommandWebDriverError extends WebDriverException {} // 9
class ObsoleteElementWebDriverError extends WebDriverException {} // 10
class ElementNotDisplayedWebDriverError extends WebDriverException {} // 11
class InvalidElementStateWebDriverError extends WebDriverException {} // 12
class UnhandledWebDriverError extends WebDriverException {} // 13
class ExpectedWebDriverError extends WebDriverException {} // 14
class ElementNotSelectableWebDriverError extends WebDriverException {} // 15
class NoSuchDocumentWebDriverError extends WebDriverException {} // 16
class UnexpectedJavascriptWebDriverError extends WebDriverException {} // 17
class NoScriptResultWebDriverError extends WebDriverException {} // 18
class XPathLookupWebDriverError extends WebDriverException {} // 19
class NoSuchCollectionWebDriverError extends WebDriverException {} // 20
class TimeOutWebDriverError extends WebDriverException {} // 21
class NullPointerWebDriverError extends WebDriverException {} // 22
class NoSuchWindowWebDriverError extends WebDriverException {} // 23
class InvalidCookieDomainWebDriverError extends WebDriverException {} // 24
class UnableToSetCookieWebDriverError extends WebDriverException {} // 25
class UnexpectedAlertOpenWebDriverError extends WebDriverException {} // 26
class NoAlertOpenWebDriverError extends WebDriverException {} // 27
class ScriptTimeoutWebDriverError extends WebDriverException {} // 28
class InvalidElementCoordinatesWebDriverError extends WebDriverException {}// 29
class IMENotAvailableWebDriverError extends WebDriverException {} // 30
class IMEEngineActivationFailedWebDriverError extends WebDriverException {}// 31
class InvalidSelectorWebDriverError extends WebDriverException {} // 32
class SessionNotCreatedWebDriverError extends WebDriverException {} // 33
class MoveTargetOutOfBoundsWebDriverError extends WebDriverException {} // 34

// Fallback
class UnrecognizedWebDriverErrorWebDriverError extends WebDriverException {}

class UnexpectedTagNameException extends WebDriverException {

  public function __construct(
      string $expected_tag_name,
      string $actual_tag_name) {
    parent::__construct(
      sprintf(
        "Element should have been \"%s\" but was \"%s\"",
        $expected_tag_name, $actual_tag_name
      )
    );
  }
}

class UnsupportedOperationException extends WebDriverException {}
