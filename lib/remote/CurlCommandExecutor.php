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

/**
 * Command executor talking to the standalone server via HTTP (curl).
 */
class CurlCommandExecutor implements WebDriverCommandExecutor
{
    /**
     * @see http://code.google.com/p/selenium/wiki/JsonWireProtocol#Command_Reference
     */
    protected $commands = array(
        DriverCommand::ACCEPT_ALERT => array('POST', '/session/:sessionId/accept_alert'),
        DriverCommand::ADD_COOKIE => array('POST', '/session/:sessionId/cookie'),
        DriverCommand::CLEAR_ELEMENT => array('POST', '/session/:sessionId/element/:id/clear'),
        DriverCommand::CLICK_ELEMENT => array('POST', '/session/:sessionId/element/:id/click'),
        DriverCommand::CLOSE => array('DELETE', '/session/:sessionId/window'),
        DriverCommand::DELETE_ALL_COOKIES => array('DELETE', '/session/:sessionId/cookie'),
        DriverCommand::DELETE_COOKIE => array('DELETE', '/session/:sessionId/cookie/:name'),
        DriverCommand::DISMISS_ALERT => array('POST', '/session/:sessionId/dismiss_alert'),
        DriverCommand::ELEMENT_EQUALS => array('GET', '/session/:sessionId/element/:id/equals/:other'),
        DriverCommand::FIND_CHILD_ELEMENT => array('POST', '/session/:sessionId/element/:id/element'),
        DriverCommand::FIND_CHILD_ELEMENTS => array('POST', '/session/:sessionId/element/:id/elements'),
        DriverCommand::EXECUTE_SCRIPT => array('POST', '/session/:sessionId/execute'),
        DriverCommand::EXECUTE_ASYNC_SCRIPT => array('POST', '/session/:sessionId/execute_async'),
        DriverCommand::FIND_ELEMENT => array('POST', '/session/:sessionId/element'),
        DriverCommand::FIND_ELEMENTS => array('POST', '/session/:sessionId/elements'),
        DriverCommand::SWITCH_TO_FRAME => array('POST', '/session/:sessionId/frame'),
        DriverCommand::SWITCH_TO_WINDOW => array('POST', '/session/:sessionId/window'),
        DriverCommand::GET => array('POST', '/session/:sessionId/url'),
        DriverCommand::GET_ACTIVE_ELEMENT => array('POST', '/session/:sessionId/element/active'),
        DriverCommand::GET_ALERT_TEXT => array('GET', '/session/:sessionId/alert_text'),
        DriverCommand::GET_ALL_COOKIES => array('GET', '/session/:sessionId/cookie'),
        DriverCommand::GET_AVAILABLE_LOG_TYPES => array('GET', '/session/:sessionId/log/types'),
        DriverCommand::GET_CURRENT_URL => array('GET', '/session/:sessionId/url'),
        DriverCommand::GET_CURRENT_WINDOW_HANDLE => array('GET', '/session/:sessionId/window_handle'),
        DriverCommand::GET_ELEMENT_ATTRIBUTE => array('GET', '/session/:sessionId/element/:id/attribute/:name'),
        DriverCommand::GET_ELEMENT_VALUE_OF_CSS_PROPERTY => array('GET', '/session/:sessionId/element/:id/css/:propertyName'),
        DriverCommand::GET_ELEMENT_LOCATION => array('GET', '/session/:sessionId/element/:id/location'),
        DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW => array('GET', '/session/:sessionId/element/:id/location_in_view'),
        DriverCommand::GET_ELEMENT_SIZE => array('GET', '/session/:sessionId/element/:id/size'),
        DriverCommand::GET_ELEMENT_TAG_NAME => array('GET', '/session/:sessionId/element/:id/name'),
        DriverCommand::GET_ELEMENT_TEXT => array('GET', '/session/:sessionId/element/:id/text'),
        DriverCommand::GET_LOG => array('POST', '/session/:sessionId/log'),
        DriverCommand::GET_PAGE_SOURCE => array('GET', '/session/:sessionId/source'),
        DriverCommand::GET_SCREEN_ORIENTATION => array('GET', '/session/:sessionId/orientation'),
        DriverCommand::GET_CAPABILITIES => array('GET', '/session/:sessionId'),
        DriverCommand::GET_TITLE => array('GET', '/session/:sessionId/title'),
        DriverCommand::GET_WINDOW_HANDLES => array('GET', '/session/:sessionId/window_handles'),
        DriverCommand::GET_WINDOW_POSITION => array('GET', '/session/:sessionId/window/:windowHandle/position'),
        DriverCommand::GET_WINDOW_SIZE => array('GET', '/session/:sessionId/window/:windowHandle/size'),
        DriverCommand::GO_BACK => array('POST', '/session/:sessionId/back'),
        DriverCommand::GO_FORWARD => array('POST', '/session/:sessionId/forward'),
        DriverCommand::IS_ELEMENT_DISPLAYED => array('GET', '/session/:sessionId/element/:id/displayed'),
        DriverCommand::IS_ELEMENT_ENABLED => array('GET', '/session/:sessionId/element/:id/enabled'),
        DriverCommand::IS_ELEMENT_SELECTED => array('GET', '/session/:sessionId/element/:id/selected'),
        DriverCommand::MAXIMIZE_WINDOW => array('POST', '/session/:sessionId/window/:windowHandle/maximize'),
        DriverCommand::MOUSE_DOWN => array('POST', '/session/:sessionId/buttondown'),
        DriverCommand::MOUSE_UP => array('POST', '/session/:sessionId/buttonup'),
        DriverCommand::CLICK => array('POST', '/session/:sessionId/click'),
        DriverCommand::DOUBLE_CLICK => array('POST', '/session/:sessionId/doubleclick'),
        DriverCommand::MOVE_TO => array('POST', '/session/:sessionId/moveto'),
        DriverCommand::NEW_SESSION => array('POST', '/session'),
        DriverCommand::QUIT => array('DELETE', '/session/:sessionId'),
        DriverCommand::REFRESH => array('POST', '/session/:sessionId/refresh'),
        DriverCommand::UPLOAD_FILE => array('POST', '/session/:sessionId/file'), // undocumented
        DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT => array('POST', '/session/:sessionId/keys'),
        DriverCommand::SET_ALERT_VALUE => array('POST', '/session/:sessionId/alert_text'),
        DriverCommand::SEND_KEYS_TO_ELEMENT => array('POST', '/session/:sessionId/element/:id/value'),
        DriverCommand::IMPLICITLY_WAIT => array('POST', '/session/:sessionId/timeouts/implicit_wait'),
        DriverCommand::SET_SCREEN_ORIENTATION => array('POST', '/session/:sessionId/orientation'),
        DriverCommand::SET_TIMEOUT => array('POST', '/session/:sessionId/timeouts'),
        DriverCommand::SET_SCRIPT_TIMEOUT => array('POST', '/session/:sessionId/timeouts/async_script'),
        DriverCommand::SET_WINDOW_POSITION => array('POST', '/session/:sessionId/window/:windowHandle/position'),
        DriverCommand::SET_WINDOW_SIZE => array('POST', '/session/:sessionId/window/:windowHandle/size'),
        DriverCommand::SUBMIT_ELEMENT => array('POST', '/session/:sessionId/element/:id/submit'),
        DriverCommand::SCREENSHOT => array('GET', '/session/:sessionId/screenshot'),
        DriverCommand::TOUCH_SINGLE_TAP => array('POST', '/session/:sessionId/touch/click'),
        DriverCommand::TOUCH_DOWN => array('POST', '/session/:sessionId/touch/down'),
        DriverCommand::TOUCH_DOUBLE_TAP => array('POST', '/session/:sessionId/touch/doubleclick'),
        DriverCommand::TOUCH_FLICK => array('POST', '/session/:sessionId/touch/flick'),
        DriverCommand::TOUCH_LONG_PRESS => array('POST', '/session/:sessionId/touch/longclick'),
        DriverCommand::TOUCH_MOVE => array('POST', '/session/:sessionId/touch/move'),
        DriverCommand::TOUCH_SCROLL => array('POST', '/session/:sessionId/touch/scroll'),
        DriverCommand::TOUCH_UP => array('POST', '/session/:sessionId/touch/up'),
    );

    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $sessionID;
    /**
     * @var resource
     */
    protected $curl;

    /**
     * @param string $url
     */
    public function __construct($url = 'http://localhost:4444/wd/hub')
    {
        $this->url = $url;
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT_MS, 300000);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt(
            $this->curl,
            CURLOPT_HTTPHEADER,
            array('Content-Type: application/json;charset=UTF-8', 'Accept: application/json')
        );
    }

    /**
     * Set connection timeout in ms.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
    }

    /**
     * @param string $sessionID
     */
    public function setSessionID($sessionID)
    {
        $this->sessionID = $sessionID;
    }

    /**
     * @param WebDriverCommand $command
     *
     * @throws InvalidArgumentException
     * @throws BadMethodCallException
     * @return WebDriverResponse
     */
    public function execute(WebDriverCommand $command)
    {
        if (empty($this->commands[$command->getName()])) {
            throw new InvalidArgumentException(sprintf('Command %s is unknown.', $command->getName()));
        }
        list($method, $url) = $this->commands[$command->getName()];
        $url = str_replace(':sessionId', $this->sessionID, $url);
        $params = $command->getParameters();
        foreach ($params as $name => $value) {
            if ($name[0] === ':') {
                $url = str_replace($name, $value, $url);
                if ($method !== 'POST') {
                    unset($params[$name]);
                }
            }
        }
        if (!empty($params) && $method !== 'POST') {
            throw new BadMethodCallException(sprintf(
                'The http method called for %s is %s but it has to be POST' .
                ' if you want to pass the JSON params %s',
                $url,
                $method,
                json_encode($params)
            ));
        }
        curl_setopt($this->curl, CURLOPT_URL, $this->url . $url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($method === 'POST' && $params && is_array($params)) {
            $encoded_params = json_encode($params);
        } else {
            $encoded_params = null;
        }
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $encoded_params);

        $raw_results = trim(curl_exec($this->curl));

        if ($error = curl_error($this->curl)) {
            $msg = sprintf(
                'Curl error thrown for http %s to %s',
                $method,
                $url
            );
            if ($params && is_array($params)) {
                $msg .= sprintf(' with params: %s', json_encode($params));
            }
            WebDriverException::throwException(-1, $msg . "\n\n" . $error, array());
        }

        $results = json_decode($raw_results, true);

        $value = null;
        if (is_array($results) && array_key_exists('value', $results)) {
            $value = $results['value'];
        }

        $message = null;
        if (is_array($value) && array_key_exists('message', $value)) {
            $message = $value['message'];
        }

        $sessionId = null;
        if (is_array($results) && array_key_exists('sessionId', $results)) {
            $sessionId = $results['sessionId'];
        }

        $status = isset($results['status']) ? $results['status'] : 0;
        WebDriverException::throwException($status, $message, $results);

        $response = new WebDriverResponse($sessionId);

        return $response->setStatus($status)->setValue($value);
    }
}
