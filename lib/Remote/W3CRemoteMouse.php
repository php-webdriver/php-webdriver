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

use Facebook\WebDriver\Interactions\Internal\WebDriverCoordinates;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\Remote\Action\WebDriverActionPerformer;
use Facebook\WebDriver\WebDriverMouse;

/**
 * Execute mouse commands for RemoteWebDriver.
 */
class W3CRemoteMouse extends RemoteMouse
{
    /**
     * @var W3CActionExecuteMethod
     */
    private $w3cExecutor;
    
    /**
     * @param W3CActionExecuteMethod $executor
     */
    public function __construct(W3CActionExecuteMethod $executor)
    {
        $this->w3cExecutor = $executor;
        parent::__construct($executor);
    }
    
    /**
     * @param null|WebDriverCoordinates $where
     *
     * @return RemoteMouse
     */
    public function click(WebDriverCoordinates $where = null)
    {
        parent::click($where);
        $this->w3cExecutor->executeAll();
        return $this;
    }

    /**
     * @param WebDriverCoordinates $where
     *
     * @return RemoteMouse
     */
    public function contextClick(WebDriverCoordinates $where = null)
    {
        parent::contextClick($where);
        $this->w3cExecutor->executeAll();
        return $this;
    }

    /**
     * @param WebDriverCoordinates $where
     *
     * @return RemoteMouse
     */
    public function doubleClick(WebDriverCoordinates $where = null)
    {
        parent::doubleClick($where);
        $this->w3cExecutor->executeAll();
        return $this;
    }

    /**
     * @param WebDriverCoordinates $where
     *
     * @return RemoteMouse
     */
    public function mouseDown(WebDriverCoordinates $where = null)
    {
        parent::mouseDown($where);
        $this->w3cExecutor->executeAll();
        return $this;
    }

    /**
     * @param WebDriverCoordinates $where
     * @param int|null $x_offset
     * @param int|null $y_offset
     *
     * @return RemoteMouse
     */
    public function mouseMove(
        WebDriverCoordinates $where = null,
        $x_offset = null,
        $y_offset = null
    ) {
        parent::mouseMove($where, $x_offset, $y_offset);
        
        return $this;
    }

    /**
     * @param WebDriverCoordinates $where
     *
     * @return RemoteMouse
     */
    public function mouseUp(WebDriverCoordinates $where = null)
    {
        parent::mouseUp($where);
        $this->w3cExecutor->executeAll();
        return $this;
    }
}
