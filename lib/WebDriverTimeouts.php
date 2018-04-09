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

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecuteMethod;

/**
 * Managing timeout behavior for WebDriver instances.
 */
class WebDriverTimeouts
{
    /**
     * @var ExecuteMethod
     */
    protected $executor;
    /**
     * @var bool
     */
    protected $w3cCompliant;

    public function __construct(ExecuteMethod $executor, $w3cCompliant = false)
    {
        $this->executor = $executor;
        $this->w3cCompliant = $w3cCompliant;
    }

    /**
     * Specify the amount of time the driver should wait when searching for an element if it is not immediately present.
     *
     * @param int $seconds Wait time in second.
     * @return WebDriverTimeouts The current instance.
     */
    public function implicitlyWait($seconds)
    {
        if ($this->w3cCompliant) {
            $this->executor->execute(
                DriverCommand::IMPLICITLY_WAIT,
                ['implicit' => $seconds * 1000]
            );

            return $this;
        }

        $this->executor->execute(
            DriverCommand::IMPLICITLY_WAIT,
            ['ms' => $seconds * 1000]
        );

        return $this;
    }

    /**
     * Set the amount of time to wait for an asynchronous script to finish execution before throwing an error.
     *
     * @param int $seconds Wait time in second.
     * @return WebDriverTimeouts The current instance.
     */
    public function setScriptTimeout($seconds)
    {
        if ($this->w3cCompliant) {
            $this->executor->execute(
                DriverCommand::SET_SCRIPT_TIMEOUT,
                ['script' => $seconds * 1000]
            );

            return $this;
        }

        $this->executor->execute(
            DriverCommand::SET_SCRIPT_TIMEOUT,
            ['ms' => $seconds * 1000]
        );

        return $this;
    }

    /**
     * Set the amount of time to wait for a page load to complete before throwing an error.
     *
     * @param int $seconds Wait time in second.
     * @return WebDriverTimeouts The current instance.
     */
    public function pageLoadTimeout($seconds)
    {
        if ($this->w3cCompliant) {
            $this->executor->execute(
                DriverCommand::SET_SCRIPT_TIMEOUT,
                ['pageLoad' => $seconds * 1000]
            );

            return $this;
        }

        $this->executor->execute(DriverCommand::SET_TIMEOUT, [
            'type' => 'page load',
            'ms' => $seconds * 1000,
        ]);

        return $this;
    }
}
