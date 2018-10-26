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

class WebDriverCommand
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    /** @var string */
    private $sessionID;
    /** @var string */
    private $name;
    /** @var array */
    private $parameters;
    /** @var string */
    private $customUrl;
    /** @var string */
    private $customMethod;

    /**
     * @param string $session_id
     * @param string $name Constant from DriverCommand
     * @param array $parameters
     * @todo In 2.0 force parameters to be an array, then remove is_array() checks in HttpCommandExecutor
     */
    public function __construct($session_id, $name, $parameters)
    {
        $this->sessionID = $session_id;
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSessionID()
    {
        return $this->sessionID;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $custom_url
     * @param string $custom_method
     * @throws WebDriverException
     */
    public function setCustomRequestParameters($custom_url, $custom_method)
    {
        if (!in_array($custom_method, [static::METHOD_GET, static::METHOD_POST])) {
            throw new WebDriverException('Invalid custom method');
        }
        $this->customMethod = $custom_method;

        if (mb_substr($custom_url, 0, 1) !== '/') {
            throw new WebDriverException('Custom url should start with /');
        }
        $this->customUrl = $custom_url;
    }

    /**
     * @throws WebDriverException
     * @return string
     */
    public function getCustomUrl()
    {
        if ($this->customUrl === null) {
            throw new WebDriverException('Custom url is not set');
        }

        return $this->customUrl;
    }

    /**
     * @throws WebDriverException
     * @return string
     */
    public function getCustomMethod()
    {
        if ($this->customUrl === null) {
            throw new WebDriverException('Custom url is not set');
        }

        return $this->customMethod;
    }
}
