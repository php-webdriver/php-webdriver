# Changelog
This project versioning adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
- Fixed FirefoxProfile to support installation of extensions with custom namespace prefix in their manifest file
- Comply codestyle with [PSR-2](http://www.php-fig.org/psr/psr-2/)

## 1.1.2 - 2016-06-04
- Added ext-curl to composer.json
- Added CHANGELOG.md
- Added CONTRIBUTING.md with information and rules for contributors

## 1.1.1 - 2015-12-31
- Fixed strict standards error in `ChromeDriver`
- Added unit tests for `WebDriverCommand` and `DesiredCapabilities`
- Fixed retrieving temporary path name in `FirefoxDriver` when `open_basedir` restriction is in effect 

## 1.1.0 - 2015-12-08
- FirefoxProfile improved - added possibility to set RDF file and to add datas for extensions
- Fixed setting 0 second timeout of `WebDriverWait`
