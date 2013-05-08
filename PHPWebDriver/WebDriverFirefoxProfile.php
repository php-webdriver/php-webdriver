<?php
// Copyright 2013-present Element 34
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

include_once('WebDriverExceptions.php');

class PHPWebDriver_WebDriverFirefoxProfile {
    public function __construct($profile_directory) {
        // make a temp directory that we stash the profile in
        $tempfile = tempnam(sys_get_temp_dir(),'');
        if (file_exists($tempfile)) {
            unlink($tempfile);
        }
        mkdir($tempfile);
        if (is_dir($tempfile)) {
            $this->profile_dir = $tempfile;
        }

        // cop things over
        $this->copy_profile($profile_directory, $this->profile_dir);
    }

    private function make_temp_dir() {

    }

    public function encoded() {
        $zip = new \ZipArchive();

        $filename = $this->profile_dir . '.zip';
        if(($zip->open($filename, \ZipArchive::OVERWRITE)) !== true) {
            throw new \SaunterPHP_Framework_Exception("Unable to create profile zip ${$profile_path}");
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->profile_dir, $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $key=>$value) {
            $zip->addFile($key, substr($key, strlen($this->profile_dir) + 1)) or die ("ERROR: Could not add file: $key");
        }

        $zip->close();

        // base64 the zip
        $contents = fread(fopen($filename, 'r+b'), filesize($filename));
        $encoded = base64_encode($contents);
        return $encoded;
    }

    private function copy_profile($src, $dst) {
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->copy_profile($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }


}