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
final class PHPWebDriver_WebDriverCurlException extends Exception {} // When curls fail

abstract class PHPWebDriver_WebDriverException extends Exception {
  private $results;

  public function __construct($message, $results = null) {
    parent::__construct($message);
    $this->results = $results;
  }

  public function getResults() {
    return $this->results;
  }
}
class PHPWebDriver_IndexOutOfBoundsWebDriverError extends PHPWebDriver_WebDriverException {}        // 1
class PHPWebDriver_NoCollectionWebDriverError extends PHPWebDriver_WebDriverException {}            // 2
class PHPWebDriver_NoStringWebDriverError extends PHPWebDriver_WebDriverException {}                // 3
class PHPWebDriver_NoStringLengthWebDriverError extends PHPWebDriver_WebDriverException {}          // 4
class PHPWebDriver_NoStringWrapperWebDriverError extends PHPWebDriver_WebDriverException {}         // 5
class PHPWebDriver_NoSuchDriverWebDriverError extends PHPWebDriver_WebDriverException {}            // 6
class PHPWebDriver_NoSuchElementWebDriverError extends PHPWebDriver_WebDriverException {}           // 7
class PHPWebDriver_NoSuchFrameWebDriverError extends PHPWebDriver_WebDriverException {}             // 8
class PHPWebDriver_UnknownCommandWebDriverError extends PHPWebDriver_WebDriverException {}          // 9
class PHPWebDriver_ObsoleteElementWebDriverError extends PHPWebDriver_WebDriverException {}         // 10
class PHPWebDriver_ElementNotDisplayedWebDriverError extends PHPWebDriver_WebDriverException {}     // 11
class PHPWebDriver_InvalidElementStateWebDriverError extends PHPWebDriver_WebDriverException {}     // 12
class PHPWebDriver_UnhandledWebDriverError extends PHPWebDriver_WebDriverException {}               // 13
class PHPWebDriver_ExpectedWebDriverError extends PHPWebDriver_WebDriverException {}                // 14
class PHPWebDriver_ElementNotSelectableWebDriverError extends PHPWebDriver_WebDriverException {}    // 15
class PHPWebDriver_NoSuchDocumentWebDriverError extends PHPWebDriver_WebDriverException {}          // 16
class PHPWebDriver_UnexpectedJavascriptWebDriverError extends PHPWebDriver_WebDriverException {}    // 17
class PHPWebDriver_NoScriptResultWebDriverError extends PHPWebDriver_WebDriverException {}          // 18
class PHPWebDriver_XPathLookupWebDriverError extends PHPWebDriver_WebDriverException {}             // 19
class PHPWebDriver_NoSuchCollectionWebDriverError extends PHPWebDriver_WebDriverException {}        // 20
class PHPWebDriver_TimeOutWebDriverError extends PHPWebDriver_WebDriverException {}                 // 21
class PHPWebDriver_NullPointerWebDriverError extends PHPWebDriver_WebDriverException {}             // 22
class PHPWebDriver_NoSuchWindowWebDriverError extends PHPWebDriver_WebDriverException {}            // 23
class PHPWebDriver_InvalidCookieDomainWebDriverError extends PHPWebDriver_WebDriverException {}     // 24
class PHPWebDriver_UnableToSetCookieWebDriverError extends PHPWebDriver_WebDriverException {}       //25
class PHPWebDriver_UnexpectedAlertOpenWebDriverError extends PHPWebDriver_WebDriverException {}     // 26
class PHPWebDriver_NoAlertOpenWebDriverError extends PHPWebDriver_WebDriverException {}             // 27
class PHPWebDriver_ScriptTimeoutWebDriverError extends PHPWebDriver_WebDriverException {}           // 28
class PHPWebDriver_InvalidElementCoordinatesWebDriverError extends PHPWebDriver_WebDriverException {}   // 29
class PHPWebDriver_IMENotAvailableWebDriverError extends PHPWebDriver_WebDriverException {}         // 30
class PHPWebDriver_IMEEngineActivationFailedWebDriverError extends PHPWebDriver_WebDriverException {}   // 31
class PHPWebDriver_InvalidSelectorWebDriverError extends PHPWebDriver_WebDriverException {}         // 32
class PHPWebDriver_InvalidProxyConfiguration extends PHPWebDriver_WebDriverException {}         // xx
class PHPWebDriver_UnexpectedTagNameException extends PHPWebDriver_WebDriverException {}         // xx
class PHPWebDriver_InternalServerError extends PHPWebDriver_WebDriverException {}         // 500
class PHPWebDriver_ChainError extends PHPWebDriver_WebDriverException {}         // xx
    