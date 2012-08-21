<?php

require_once 'HTTP/Server.php';
require_once 'Net/Server/Driver/Fork.php';

class LocalWebServer extends HTTP_Server {
    function __construct($hostname, $port, $driver = 'Fork') {
        if ($port == 0) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
            socket_bind($socket, '127.0.0.1', 0);
            socket_getsockname($socket, $socket_address, $port);
            socket_close($socket);
        }
        $this->_driver = &new E34_Net_Server_Driver_Fork($hostname, $port);
        $this->_driver->readEndCharacter  = "\r\n\r\n";
        $this->_driver->setCallbackObject($this);
    }
    
    function GET($clientId, &$request) {
        $path_info       = $request->getPathInfo();
        $path_translated = $this->documentRoot . $path_info;

        $headers = array();
        
        //  path does not exist
        if (!file_exists($path_translated)) {
            return array(
                            "code" => 404
                        );
        }

        if (is_readable($path_translated) ) {
            $body = fopen($path_translated, "rb");
        }

        return array(
                        "code"    => 200,
                        "headers" => $headers,
                        "body"    => $body
                    );
    }
}

class E34_Net_Server_Driver_Fork extends Net_Server_Driver_Fork {
    function serviceRequest() {
        while (true) {
            $readFDs = array($this->clientFD[0]);

            //    block and wait for data
            $ready = @socket_select($readFDs, $this->null, $this->null, null);
            // the auto-request of favicon.ico gives things fits, so null check
            if ($ready === false && $this->clientFD[0] && socket_last_error($this->clientFD[0]) !== 0) {
                $this->_sendDebugMessage('socket_select() failed.');
                $this->shutdown();
            }

            if (in_array($this->clientFD[0], $readFDs) && (!$ready===false)) {
                $data = $this->readFromSocket();

                // empty data => connection was closed
                if ($data === false) {
                    $this->_sendDebugMessage('Connection closed by peer');
                    $this->closeConnection();
                } else {
                    $this->_sendDebugMessage('Received ' . trim($data) . ' from ' . $this->_getDebugInfo());

                    if (method_exists($this->callbackObj, 'onReceiveData')) {
                        $this->callbackObj->onReceiveData(0, $data);
                    }
                }
            }
        }
    }
}

