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
 * WebDriverCapabilityType contains all constants defined in the WebDriver Wire Protocol.
 *
 * @codeCoverageIgnore
 */
class WebDriverCapabilityType
{
    const BROWSER_NAME = 'browserName';
    const VERSION = 'version';
    const PLATFORM = 'platformName';
    const JAVASCRIPT_ENABLED = 'javascriptEnabled';
    const TAKES_SCREENSHOT = 'takesScreenshot';
    const HANDLES_ALERTS = 'handlesAlerts';
    const DATABASE_ENABLED = 'databaseEnabled';
    const LOCATION_CONTEXT_ENABLED = 'locationContextEnabled';
    const APPLICATION_CACHE_ENABLED = 'applicationCacheEnabled';
    const BROWSER_CONNECTION_ENABLED = 'browserConnectionEnabled';
    const CSS_SELECTORS_ENABLED = 'cssSelectorsEnabled';
    const WEB_STORAGE_ENABLED = 'webStorageEnabled';
    const ROTATABLE = 'rotatable';
    const ACCEPT_SSL_CERTS = 'acceptSslCerts';
    const NATIVE_EVENTS = 'nativeEvents';
    const PROXY = 'proxy';

    const W3C_ACCEPT_SSL_CERTS = 'acceptInsecureCerts';
    const W3C_VERSION = 'browserVersion';
    const W3C_PLATFORM = 'platformName';

    const W3C_CAPABILITY_NAME = [
        'acceptInsecureCerts',
        'browserName',
        'browserVersion',
        'platformName',
        'pageLoadStrategy',
        'proxy',
        'setWindowRect',
        'timeouts',
        'unhandledPromptBehavior',
    ];

    const PROTOCOLS_CONVERSION = [
        self::PLATFORM => self::W3C_PLATFORM,
        self::VERSION => self::W3C_VERSION,
        self::ACCEPT_SSL_CERTS => self::W3C_ACCEPT_SSL_CERTS,
    ];

    private function __construct()
    {
    }

    /**
     * @param array $capabilities
     * @return array
     */
    public static function makeW3C(array $capabilities)
    {
        $profile = !empty($capabilities['firefox_profile']) ? $capabilities['firefox_profile'] : null;
        $alwaysMatch = [];
        if (!empty($capabilities['proxy']['proxyType'])) {
            $capabilities['proxy']['proxyType'] = strtolower($capabilities['proxy']['proxyType']);
        }
    
        foreach ($capabilities as $k => $v) {
            if (!empty($v) && in_array($k, self::PROTOCOLS_CONVERSION, true)) {
                $alwaysMatch[self::PROTOCOLS_CONVERSION[$k]] = ($k === 'platform') ? strtolower($v) : $v;
            }
            if (in_array($k, self::W3C_CAPABILITY_NAME, true) || false !== strpos($k, ':')) {
                $alwaysMatch[$k] = $v;
            }
        }
    
        if ($profile) {
            $mozOpts = !empty($alwaysMatch['moz:firefoxOptions']) ? $alwaysMatch['moz:firefoxOptions'] : [];
            if (!array_key_exists('profile', $mozOpts)) {
                $newMozOpts = $mozOpts;
                $newMozOpts['profile'] = $profile;
                $alwaysMatch['moz:firefoxOptions'] = $newMozOpts;
            }
        }
    
        return [
            'alwaysMatch' => $alwaysMatch
        ];
    }
}
