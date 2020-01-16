<?php

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
