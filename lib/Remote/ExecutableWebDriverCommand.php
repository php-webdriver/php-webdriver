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

/**
 * Class ExecutableWebDriverCommand
 */
class ExecutableWebDriverCommand
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var WebDriverCommand
     */
    private $command;

    /**
     * @var WebDriverDialect
     */
    private $dialect;

    /**
     * ExecutableWebDriverCommand constructor.
     * @param string $url
     * @param string $method
     * @param WebDriverCommand $command
     * @param WebDriverDialect|null $dialect
     */
    public function __construct($url, $method, WebDriverCommand $command, WebDriverDialect $dialect = null)
    {
        $this->url = $url;
        $this->method = $method;
        $this->command = $command;
        $this->dialect = $dialect;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->command->getName();
    }

    /**
     * @return string
     */
    public function getSessionID()
    {
        return $this->command->getSessionID();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->command->getParameters();
    }

    /**
     * @return WebDriverDialect
     */
    public function getDialect()
    {
        return $this->dialect;
    }

    /**
     * @param WebDriverCommand $command
     * @return ExecutableWebDriverCommand
     */
    public static function getNewSessionCommand(WebDriverCommand $command)
    {
        return new self('/session', 'POST', $command);
    }
}
