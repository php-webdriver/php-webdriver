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

class WebDriverNavigation implements WebDriverNavigationInterface
{
    protected $executor;

    public function __construct(ExecuteMethod $executor)
    {
        $this->executor = $executor;
    }

    public function back()
    {
        $this->executor->execute(DriverCommand::GO_BACK);

        return $this;
    }

    public function forward()
    {
        $this->executor->execute(DriverCommand::GO_FORWARD);

        return $this;
    }

    public function refresh()
    {
        $this->executor->execute(DriverCommand::REFRESH);

        return $this;
    }

    public function to($url)
    {
        $params = ['url' => (string) $url];
        $this->executor->execute(DriverCommand::GET, $params);

        return $this;
    }
}
