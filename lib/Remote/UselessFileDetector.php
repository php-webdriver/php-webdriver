<?php

namespace Facebook\WebDriver\Remote;

class UselessFileDetector implements FileDetector
{
    public function getLocalFile($file)
    {
        return null;
    }
}

class_alias('Facebook\WebDriver\Remote\UselessFileDetector', 'PhpWebDriver\Remote\UselessFileDetector');
