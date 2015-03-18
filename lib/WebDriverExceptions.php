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

class WebDriverException extends Exception {

  private $results;

  /**
   * @param string $message
   * @param mixed $results
   */
  public function __construct($message, $results = null) {
    parent::__construct($message);
    $this->results = $results;
  }

  /**
   * @return mixed
   */
  public function getResults() {
    return $this->results;
  }

  /**
   * Throw WebDriverExceptions.
   * For $status_code >= 0, they are errors defined in the json wired protocol.
   * For $status_code < 0, they are errors defined in php-webdriver.
   *
   * @param int $status_code
   * @param string $message
   * @param mixed $results
   */
  public static function throwException($status_code, $message, $results) {
    switch ($status_code) {
      case -1:
        throw new WebDriverCurlException($message);
      case 0:
        // Success
        break;
      case 1:
        throw new IndexOutOfBoundsWebDriverException($message, $results);
      case 2:
        throw new NoCollectionWebDriverException($message, $results);
      case 3:
        throw new NoStringWebDriverException($message, $results);
      case 4:
        throw new NoStringLengthWebDriverException($message, $results);
      case 5:
        throw new NoStringWrapperWebDriverException($message, $results);
      case 6:
        throw new NoSuchDriverWebDriverException($message, $results);
      case 7:
        throw new NoSuchElementWebDriverException($message, $results);
      case 8:
        throw new NoSuchFrameWebDriverException($message, $results);
      case 9:
        throw new UnknownCommandWebDriverException($message, $results);
      case 10:
        throw new StaleElementReferenceWebDriverException($message, $results);
      case 11:
        throw new ElementNotVisibleWebDriverException($message, $results);
      case 12:
        throw new InvalidElementStateWebDriverException($message, $results);
      case 13:
        throw new UnknownServerWebDriverException($message, $results);
      case 14:
        throw new ExpectedWebDriverException($message, $results);
      case 15:
        throw new ElementNotSelectableWebDriverException($message, $results);
      case 16:
        throw new NoSuchDocumentWebDriverException($message, $results);
      case 17:
        throw new UnexpectedJavascriptWebDriverException($message, $results);
      case 18:
        throw new NoScriptResultWebDriverException($message, $results);
      case 19:
        throw new XPathLookupWebDriverException($message, $results);
      case 20:
        throw new NoSuchCollectionWebDriverException($message, $results);
      case 21:
        throw new TimeOutWebDriverException($message, $results);
      case 22:
        throw new NullPointerWebDriverException($message, $results);
      case 23:
        throw new NoSuchWindowWebDriverException($message, $results);
      case 24:
        throw new InvalidCookieDomainWebDriverException($message, $results);
      case 25:
        throw new UnableToSetCookieWebDriverException($message, $results);
      case 26:
        throw new UnexpectedAlertOpenWebDriverException($message, $results);
      case 27:
        throw new NoAlertOpenWebDriverException($message, $results);
      case 28:
        throw new ScriptTimeoutWebDriverException($message, $results);
      case 29:
        throw new InvalidCoordinatesWebDriverException($message, $results);
      case 30:
        throw new IMENotAvailableWebDriverException($message, $results);
      case 31:
        throw new IMEEngineActivationFailedWebDriverException($message, $results);
      case 32:
        throw new InvalidSelectorWebDriverException($message, $results);
      case 33:
        throw new SessionNotCreatedWebDriverException($message, $results);
      case 34:
        throw new MoveTargetOutOfBoundsWebDriverException($message, $results);
      default:
        throw new UnrecognizedExceptionWebDriverException($message, $results);
    }
  }
}
