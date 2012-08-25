<?php
namespace WebDriver;

require_once('dashboard.php');
require_once(dirname(__FILE__) . '/../../../PHPWebDriver/WebDriverWait.php');
require_once(dirname(__FILE__) . '/../../../PHPWebDriver/WebDriverBy.php');

class SauceLoginPage {
  private $locators = array(
      "username" => array(\PHPWebDriver_WebDriverBy::ID, 'username'),
      "password" => array(\PHPWebDriver_WebDriverBy::ID, 'password'),
      "submit button" => array(\PHPWebDriver_WebDriverBy::ID, 'submit'),
      "errors" => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, '.error')
  );

  function __construct($session) {
    $this->session = $session;
  }
  
  function __get($property) {
    switch($property) {
      case "errors":
        list($type, $string) = $this->locators[$property];
        $e = $this->session->element($type, $string);
        return $e->text();
      case "title":
        return $this->session->title();
      default:
        return $this->$property;
    }
  }

  function __set($property, $value) {
    switch($property) {
      case "username":
      case "password":
        list($type, $string) = $this->locators[$property];
        $e = $this->session->element($type, $string);
        $e->sendKeys($value);
        break;
      default:
        $this->$property = $value;
    }
  }

  function open() {
    $this->session->open("https://saucelabs.com/login");
    return $this;
  }
  
  function wait_until_loaded() {
    $w = new \PHPWebDriver_WebDriverWait($this->session, 30, 0.5, array("locator" => $this->locators['submit button']));
    $w->until(
      function($session, $extra_arguments) {
        list($type, $string) = $extra_arguments['locator'];
        return $session->element($type, $string);
      }
    );
    return $this;
  }
  
  function validate() {
    assert('$this->title == "Login - Sauce Labs" /* title should be "Login - Sauce Labs" */');
    return $this;
  }
  
  function login_as($username, $password, $success=true) {
    $this->username = $username;
    $this->password = $password;

    list($type, $string) = $this->locators['submit button'];
    $e = $this->session->element($type, $string);
    $e->click();

    if ($success) {
      $p = new \DashboardPage($this->session);
      $p->wait_until_loaded();
      return $p;
    } else {
    $w = new \PHPWebDriver_WebDriverWait($this->session, 30, 0.5, array("locator" => $this->locators['errors']));
      $w->until(
        function($session, $extra_arguments) {
          list($type, $string) = $extra_arguments['locator'];
          $e = $session->element($type, $string);
          return $e->displayed();
        }
      );
      return $this;
    }
  }
}
