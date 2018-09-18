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

class WebDriverDialect
{
    const W3C_DIALECT = 'W3C';
    const JSON_WIRE_PROTOCOL_DIALECT = 'OSS';

    /** @var string */
    private $dialect;

    /**
     * WebDriverDialect constructor.
     * @param string $dialect
     */
    private function __construct($dialect)
    {
        $this->dialect = $dialect;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dialect;
    }

    /**
     * @return WebDriverDialect
     */
    public static function createW3C()
    {
        return new self(self::W3C_DIALECT);
    }

    /**
     * @return WebDriverDialect
     */
    public static function createJsonWireProtocol()
    {
        return new self(self::JSON_WIRE_PROTOCOL_DIALECT);
    }

    /**
     * @return bool
     */
    public function isW3C()
    {
        return $this->dialect === self::W3C_DIALECT;
    }

    /**
     * @param array $result
     * @return WebDriverDialect
     */
    public static function guessByNewSessionResultBody(array $result)
    {
        if (!isset($result['status'])) {
            return self::createW3C();
        }

        return self::createJsonWireProtocol();
    }
}
