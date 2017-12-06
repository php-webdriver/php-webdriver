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

use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\BaseTestListener;

class ReportSauceLabsStatusListener extends BaseTestListener
{
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        if (!$test instanceof WebDriverTestCase || !$test->driver instanceof RemoteWebDriver) {
            return;
        }

        /** @var WebDriverTestCase $test */
        if (!$test->isSauceLabsBuild()) {
            return;
        }

        $testStatus = $test->getStatus();

        if ($this->testWasSkippedOrIncomplete($testStatus)) {
            return;
        }

        $endpointUrl = sprintf(
            'https://saucelabs.com/rest/v1/%s/jobs/%s',
            getenv('SAUCE_USERNAME'),
            $test->driver->getSessionID()
        );

        $data = [
            'passed' => ($testStatus === \PHPUnit_Runner_BaseTestRunner::STATUS_PASSED),
            'custom-data' => ['message' => $test->getStatusMessage()],
        ];

        $this->submitToSauceLabs($endpointUrl, $data);
    }

    /**
     * @param int $testStatus
     * @return bool
     */
    private function testWasSkippedOrIncomplete($testStatus)
    {
        if ($testStatus === \PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED
            || $testStatus === \PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE) {
            return true;
        }

        return false;
    }

    /**
     * @param string $url
     * @param array $data
     */
    private function submitToSauceLabs($url, array $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_USERPWD, getenv('SAUCE_USERNAME') . ':' . getenv('SAUCE_ACCESS_KEY'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        // Disable sending 'Expect: 100-Continue' header, as it is causing issues with eg. squid proxy
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:']);

        curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \Exception(sprintf('Error publishing test results to SauceLabs: %s', curl_error($curl)));
        }

        curl_close($curl);
    }
}
