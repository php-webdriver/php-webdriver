#!/bin/sh

# All commands below must no fail
set -e

# Be in the root dir
cd "$(dirname $0)/../"

find tests/ -type f -print0 | xargs -0 sed -i 's/function setUpBeforeClass(): void/function setUpBeforeClass()/g';
find tests/ -type f -print0 | xargs -0 sed -i 's/function setUp(): void/function setUp()/g';
find tests/ -type f -print0 | xargs -0 sed -i 's/function tearDown(): void/function tearDown()/g';

sed -i 's/endTest(\\PHPUnit\\Framework\\Test \$test, float \$time): void/endTest(\\PHPUnit_Framework_Test \$test, \$time)/g' tests/functional/ReportSauceLabsStatusListener.php;
sed -i 's/: void/ /g' tests/functional/ReportSauceLabsStatusListener.php;
# Drop the listener from the config file
sed -i '/<listeners>/,+2d' phpunit.xml.dist;
sed -i 's/function runBare(): void/function runBare()/g' tests/functional/WebDriverTestCase.php;

# Return back to original dir
cd - > /dev/null
