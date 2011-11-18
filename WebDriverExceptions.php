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
