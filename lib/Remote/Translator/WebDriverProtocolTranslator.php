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

namespace Facebook\WebDriver\Remote\Translator;

use Facebook\WebDriver\Remote\ExecutableWebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverCommand;

interface WebDriverProtocolTranslator
{
    /**
     * @param WebDriverCommand $command
     * @return ExecutableWebDriverCommand
     */
    public function translateCommand(WebDriverCommand $command);

    /**
     * @param array $raw_element
     * @return string
     */
    public function translateElement($raw_element);

    /**
     * @param string $command_name
     * @param array $params
     * @return array
     */
    public function translateParameters($command_name, $params);

    /**
     * @param string $command_name
     * @param mixed $value
     * @return mixed
     */
    public function translateResponse($command_name, $value);
}
