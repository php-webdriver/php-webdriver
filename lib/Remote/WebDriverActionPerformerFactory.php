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
use Facebook\WebDriver\Remote\Action\JsonWireProtocolActionPerformer;
use Facebook\WebDriver\Remote\Action\W3CProtocolActionPerformer;

class WebDriverActionPerformerFactory
{
    /**
     * @param WebDriverDialect $dialect
     * @param RemoteExecuteMethod $interactionExecutionMethod
     * @throws WebDriverException
     * @return JsonWireProtocolActionPerformer|W3CProtocolActionPerformer
     */
    public static function create(
        WebDriverDialect $dialect,
        RemoteExecuteMethod $interactionExecutionMethod
    ) {
        if (!$dialect->isW3C()) {
            return new JsonWireProtocolActionPerformer();
        }
        if ($interactionExecutionMethod instanceof BunchActionExecuteMethod) {
            return new W3CProtocolActionPerformer($interactionExecutionMethod);
        }
        throw new WebDriverException('Cannot resolve dialect: ' . $dialect);
    }
}
