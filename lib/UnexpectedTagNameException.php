<?php

class UnexpectedTagNameException extends WebDriverException {

  public function __construct(
      string $expected_tag_name,
      string $actual_tag_name) {
    parent::__construct(
      sprintf(
        "Element should have been \"%s\" but was \"%s\"",
        $expected_tag_name, $actual_tag_name
      )
    );
  }
}

