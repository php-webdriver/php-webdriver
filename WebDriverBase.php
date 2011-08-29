<?php
// Copyright 2004-present Facebook. All Rights Reserved.

abstract class WebDriverBase {
  abstract protected function methods();

  protected $url;
  public function __construct($url = 'http://localhost:4444/wd/hub') {
    $this->url = $url;
  }

  protected function curl($http_method,
                          $command,
                          $params = null,
                          $extra_opts = array()) {
    if ($params && is_array($params) && $http_method !== 'POST') {
      throw(new Exception(sprintf(
        'The http method called for %s is %s but it has to be POST' .
        ' if you want to pass the JSON params %s',
        $command,
        $http_method,
        json_encode($params))));
    }

    $url = sprintf('%s%s', $this->url, $command);
    if ($params && !is_array($params)) {
      $url .= '/' . $params;
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
                array('application/json;charset=UTF-8'));

    if ($http_method === 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
      if ($params && is_array($params)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
      }
    } else if ($http_method == 'DELETE') {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    foreach ($extra_opts as $option => $value) {
      curl_setopt($curl, $option, $value);
    }

    $raw_results = trim(curl_exec($curl));
    $results = json_decode($raw_results, true);

    $info = curl_getinfo($curl);
    if ($error = curl_error($curl)) {
      throw(new Exception(sprintf(
        'Curl error for request %s: %s',
        $url,
        $error)));
    }
    curl_close($curl);

    return array('value' => $results['value'], 'info' => $info);
  }

  public function __call($name, $arguments) {
    if (count($arguments) > 1) {
      throw(new Exception(
        'Commands should have at most only one parameter,' .
        ' which should be the JSON Parameter object'));
    }

    if (preg_match('/^(get|post|delete)/', $name, $matches)) {
      $http_method = strtoupper($matches[0]);
      $webdriver_command = strtolower(substr($name, strlen($http_method)));
      $default_http_method = $this->getHTTPMethod($webdriver_command);
      if ($http_method === $default_http_method) {
        throw(new Exception(sprintf(
          '%s is the default http method for %s.  Please just call %s().',
          $http_method,
          $webdriver_command,
          $webdriver_command)));
      }
      if (!in_array($http_method, $this->methods()[$webdriver_command])) {
        throw(new Exception(sprintf(
          '%s is not an available http method for the command %s.',
          $http_method,
          $webdriver_command)));
      }
    } else {
      $webdriver_command = $name;
      $http_method = $this->getHTTPMethod($webdriver_command);
    }

    $results = $this->curl($http_method,
                           '/' . $webdriver_command,
                           $arguments[0]);

    return $results['value'];
  }

  private function getHTTPMethod($webdriver_command) {
    if (!array_key_exists($webdriver_command, $this->methods())) {
      throw(new Exception(sprintf(
        '%s is not a valid webdriver command.',
        $webdriver_command)));
    }

    $http_methods = $this->methods()[$webdriver_command];
    return is_array($http_methods) ? $http_methods[0] : $http_methods;
  }
}
