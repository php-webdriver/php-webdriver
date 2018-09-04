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

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\Translator\W3CProtocolTranslator;

class BunchActionExecuteMethod extends RemoteExecuteMethod
{
    /**
     * @var array | array[]
     */
    private $actions = [];
    
    /**
     * @param string $command_name
     * @param array $parameters
     * @return mixed
     */
    public function execute($command_name, array $parameters = [])
    {
        $this->actions[] = ['commandName' => $command_name, 'params' => $parameters];
    }
    
    /**
     * @throws WebDriverException
     */
    public function executeAll()
    {
        $w3cActions = [];
        $translator = new W3CProtocolTranslator();
        foreach ($this->actions as $action) {
            switch ($action['commandName']) {
                case DriverCommand::MOVE_TO:
                case DriverCommand::MOUSE_DOWN:
                case DriverCommand::MOUSE_UP:
                case DriverCommand::CLICK:
                case DriverCommand::DOUBLE_CLICK:
                    $w3cActions = array_merge(
                        $w3cActions,
                        $translator->translateParameters($action['commandName'], $action['params'])
                    );
                    break;
            }
        }
        $this->actions = [];
        $this->driver->execute(DriverCommand::ACTIONS, ['actions' => [$translator->encodeActions($w3cActions)]]);
    }
}
