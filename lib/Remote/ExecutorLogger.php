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

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class ExecutorLogger implements LoggerInterface
{
    use LoggerTrait;

    const LOG_FILENAME = __DIR__ . '/../../logs/http-executor-%s.log';

    /** @var bool|resource */
    private $handle;

    /**
     * LoggerExecutor constructor.
     * @param string|null $logFilenamePrefix
     */
    public function __construct($logFilenamePrefix = null)
    {
        if (!\is_string($logFilenamePrefix)) {
            $logFilenamePrefix = \date('Y-m-d');
        }
        $handle = fopen(sprintf(self::LOG_FILENAME, $logFilenamePrefix), 'a+');
        if (is_resource($handle)) {
            $this->handle = $handle;
        }
    }

    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    public function log($level, $message, array $context = [])
    {
        if ($this->handle) {
            fwrite($this->handle, $level . ': ' . $message . print_r($context, true) . PHP_EOL . PHP_EOL);
        }
    }
}
