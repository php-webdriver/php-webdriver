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

use BadMethodCallException;
use Facebook\WebDriver\Exception\WebDriverCurlException;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\WebDriverCommandExecutor;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Command executor talking to the standalone server via HTTP.
 */
class HttpCommandExecutor implements WebDriverCommandExecutor
{
    const DEFAULT_HTTP_HEADERS = [
        'Content-Type: application/json;charset=UTF-8',
        'Accept: application/json',
    ];

    /**
     * @var string
     */
    protected $url;
    /**
     * @var resource
     */
    protected $curl;
    /**
     * @var LoggerInterface | null
     */
    protected $logger;
    
    /**
     * @param string $url
     * @param string|null $http_proxy
     * @param int|null $http_proxy_port
     */
    public function __construct($url, $http_proxy = null, $http_proxy_port = null)
    {
        $this->url = $url;
        $this->curl = curl_init();

        if (!empty($http_proxy)) {
            curl_setopt($this->curl, CURLOPT_PROXY, $http_proxy);
            if ($http_proxy_port !== null) {
                curl_setopt($this->curl, CURLOPT_PROXYPORT, $http_proxy_port);
            }
        }

        // Get credentials from $url (if any)
        $matches = null;
        if (preg_match("/^(https?:\/\/)(.*):(.*)@(.*?)/U", $url, $matches)) {
            $this->url = $matches[1] . $matches[4];
            $auth_creds = $matches[2] . ':' . $matches[3];
            curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($this->curl, CURLOPT_USERPWD, $auth_creds);
        }

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, static::DEFAULT_HTTP_HEADERS);
        $this->setRequestTimeout(30000);
        $this->setConnectionTimeout(30000);
        
        $this->logger = new NullLogger();
    }
    
    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set timeout for the connect phase
     *
     * @param int $timeout_in_ms Timeout in milliseconds
     * @return HttpCommandExecutor
     */
    public function setConnectionTimeout($timeout_in_ms)
    {
        // There is a PHP bug in some versions which didn't define the constant.
        curl_setopt(
            $this->curl,
            /* CURLOPT_CONNECTTIMEOUT_MS */
            156,
            $timeout_in_ms
        );

        return $this;
    }

    /**
     * Set the maximum time of a request
     *
     * @param int $timeout_in_ms Timeout in milliseconds
     * @return HttpCommandExecutor
     */
    public function setRequestTimeout($timeout_in_ms)
    {
        // There is a PHP bug in some versions (at least for PHP 5.3.3) which
        // didn't define the constant.
        curl_setopt(
            $this->curl,
            /* CURLOPT_TIMEOUT_MS */
            155,
            $timeout_in_ms
        );

        return $this;
    }

    /**
     * @param ExecutableWebDriverCommand $command
     *
     * @throws WebDriverException
     * @return array
     */
    public function execute(ExecutableWebDriverCommand $command)
    {
        $http_method = $command->getMethod();
        $url = $command->getUrl();
        $url = str_replace(':sessionId', $command->getSessionID(), $url);
        $params = $command->getParameters();
        foreach ($params as $name => $value) {
            if ($name[0] === ':') {
                $url = str_replace($name, $value, $url);
                unset($params[$name]);
            }
        }

        if ($params && is_array($params) && $http_method !== 'POST') {
            throw new BadMethodCallException(sprintf(
                'The http method called for %s is %s but it has to be POST' .
                ' if you want to pass the JSON params %s',
                $url,
                $http_method,
                json_encode($params)
            ));
        }

        curl_setopt($this->curl, CURLOPT_URL, $this->url . $url);
        
        curl_setopt($this->curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($this->curl, CURLOPT_STDERR, $verbose);
        
        // https://github.com/facebook/php-webdriver/issues/173
        if ($command->getName() === DriverCommand::NEW_SESSION) {
            curl_setopt($this->curl, CURLOPT_POST, 1);
        } else {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $http_method);
        }

        if (in_array($http_method, ['POST', 'PUT'])) {
            // Disable sending 'Expect: 100-Continue' header, as it is causing issues with eg. squid proxy
            // https://tools.ietf.org/html/rfc7231#section-5.1.1
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array_merge(static::DEFAULT_HTTP_HEADERS, ['Expect:']));
        } else {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, static::DEFAULT_HTTP_HEADERS);
        }

        $encoded_params = null;

        if ($http_method === 'POST' && $params && is_array($params)) {
            $encoded_params = json_encode($params);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $encoded_params);
        }

        $raw_results = trim(curl_exec($this->curl));
        rewind($verbose);
        $curlContent = stream_get_contents($verbose);
        
        if ($error = curl_error($this->curl)) {
            $msg = sprintf(
                'Curl error thrown for http %s to %s',
                $http_method,
                $url
            );
            if ($params && is_array($params)) {
                $msg .= sprintf(' with params: %s', json_encode($params));
            }
            $this->logger->error($msg . PHP_EOL . $curlContent);
            throw new WebDriverCurlException($msg . "\n\n" . $error);
        }

        $results = json_decode($raw_results, true);

        if ($results === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('JSON: ' . json_last_error() . PHP_EOL . $curlContent);
            throw new WebDriverException(
                sprintf(
                    "JSON decoding of remote response failed.\n" .
                    "Error code: %d\n" .
                    "The response:  '%s'\n",
                    json_last_error(),
                    $raw_results
                )
            );
        }
    
        $this->logger->debug(
            $curlContent,
            [
                'url' => $this->url . $url,
                'payload' => $encoded_params,
                'response' => $results
            ]
        );
        
        return $results;
    }

    /**
     * @return string
     */
    public function getAddressOfRemoteServer()
    {
        return $this->url;
    }
}
