<?php

namespace Facebook\WebDriver\Remote\Action;


use Facebook\WebDriver\Remote\ExecutableWebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverCommand;
use Facebook\WebDriver\WebDriverAction;

interface WebDriverActionPerformer
{
    /**
     * @param array | WebDriverAction[] $actions
     * @return void
     */
    public function perform(array $actions);
}