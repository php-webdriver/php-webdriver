<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestListener;
use Throwable;

class ReportSauceLabsStatusListener implements TestListener
{
    public function endTest(\PHPUnit\Framework\Test $test, float $time): void
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
            'passed' => ($testStatus === \PHPUnit\Runner\BaseTestRunner::STATUS_PASSED),
            'custom-data' => ['message' => $test->getStatusMessage()],
        ];

        $this->submitToSauceLabs($endpointUrl, $data);
    }

    public function addError(\PHPUnit\Framework\Test $test, Throwable $t, float $time): void
    {
    }

    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $w, float $time): void
    {
    }

    public function addFailure(
        \PHPUnit\Framework\Test $test,
        \PHPUnit\Framework\AssertionFailedError $e,
        float $time
    ): void {
    }

    public function addIncompleteTest(\PHPUnit\Framework\Test $test, Throwable $t, float $time): void
    {
    }

    public function addRiskyTest(\PHPUnit\Framework\Test $test, Throwable $t, float $time): void
    {
    }

    public function addSkippedTest(\PHPUnit\Framework\Test $test, Throwable $t, float $time): void
    {
    }

    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite): void
    {
    }

    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite): void
    {
    }

    public function startTest(\PHPUnit\Framework\Test $test): void
    {
    }

    private function testWasSkippedOrIncomplete(int $testStatus): bool
    {
        if ($testStatus === \PHPUnit\Runner\BaseTestRunner::STATUS_SKIPPED
            || $testStatus === \PHPUnit\Runner\BaseTestRunner::STATUS_INCOMPLETE) {
            return true;
        }

        return false;
    }

    private function submitToSauceLabs(string $url, array $data): void
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_USERPWD, getenv('SAUCE_USERNAME') . ':' . getenv('SAUCE_ACCESS_KEY'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR));
        // Disable sending 'Expect: 100-Continue' header, as it is causing issues with eg. squid proxy
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:']);

        curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \Exception(sprintf('Error publishing test results to SauceLabs: %s', curl_error($curl)));
        }

        curl_close($curl);
    }
}
