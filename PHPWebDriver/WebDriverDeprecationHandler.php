<?php

function PHPWebDriver_deprecation_handler($level, $message, $file, $line, $context) {
    //Handle user errors, warnings, and notices ourself
    if($level === E_USER_DEPRECATED) {
        echo 'Deprecated Warning:'. $message . '\n';
        return(true); //And prevent the PHP error handler from continuing
    }
    return(false); //Otherwise, use PHP's error handler
}

set_error_handler('PHPWebDriver_deprecation_handler');