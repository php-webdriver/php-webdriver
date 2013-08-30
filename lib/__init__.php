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

require_once('WebDriver.php');
require_once('WebDriverAction.php');
require_once('WebDriverAlert.php');
require_once('WebDriverBy.php');
require_once('WebDriverDimension.php');
require_once('WebDriverElement.php');
require_once('WebDriverExceptions.php');
require_once('WebDriverExpectedCondition.php');
require_once('WebDriverHasInputDevices.php');
require_once('WebDriverKeys.php');
require_once('WebDriverNavigation.php');
require_once('WebDriverMouse.php');
require_once('WebDriverOptions.php');
require_once('WebDriverPoint.php');
require_once('WebDriverSelect.php');
require_once('WebDriverTargetLocator.php');
require_once('WebDriverTimeouts.php');
require_once('WebDriverWait.php');
require_once('WebDriverWindow.php');
require_once('interactions/WebDriverActions.php');
require_once('interactions/internal/WebDriverMouseAction.php');
require_once('interactions/WebDriverCompositeAction.php');
require_once('interactions/internal/WebDriverButtonReleaseAction.php');
require_once('interactions/internal/WebDriverClickAction.php');
require_once('interactions/internal/WebDriverClickAndHoldAction.php');
require_once('interactions/internal/WebDriverContextClickAction.php');
require_once('interactions/internal/WebDriverCoordinates.php');
require_once('interactions/internal/WebDriverDoubleClickAction.php');
require_once('interactions/internal/WebDriverMouseMoveAction.php');
require_once('interactions/internal/WebDriverMoveToOffsetAction.php');
require_once('internal/WebDriverLocatable.php');
require_once('remote/RemoteMouse.php');
require_once('remote/RemoteWebDriver.php');
require_once('remote/RemoteWebElement.php');
require_once('remote/WebDriverBrowserType.php');
require_once('remote/WebDriverCapabilityType.php');
require_once('remote/WebDriverCommandExecutor.php');
