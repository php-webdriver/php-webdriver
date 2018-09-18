<?php

namespace Facebook\WebDriver\Remote\Action;

use Facebook\WebDriver\WebDriverAction;

interface WebDriverActionPerformer
{
    /**
     * @param array | WebDriverAction[] $actions
     */
    public function perform(array $actions);
}
