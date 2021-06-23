<?php

namespace PhpWebDriver\WebDriver\Remote;

class UselessFileDetector implements FileDetector
{
    public function getLocalFile($file)
    {
        return null;
    }
}

class_alias(\PhpWebDriver\WebDriver\Remote\UselessFileDetector::class, \Facebook\WebDriver\Remote\UselessFileDetector::class);
