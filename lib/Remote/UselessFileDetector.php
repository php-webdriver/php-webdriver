<?php

namespace PhpWebDriver\WebDriver\Remote;

class UselessFileDetector implements FileDetector
{
    public function getLocalFile($file)
    {
        return null;
    }
}
