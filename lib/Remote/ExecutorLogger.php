<?php

namespace Facebook\WebDriver\Remote;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class ExecutorLogger implements LoggerInterface
{
    use LoggerTrait;
    
    const LOG_FILENAME = __DIR__ . '/../../logs/http-executor.log';
    
    /** @var bool|resource */
    private $handle;
    
    /**
     * LoggerExecutor constructor.
     */
    public function __construct()
    {
        $handle = fopen(sprintf(self::LOG_FILENAME, date('Y-m-d')), 'a+');
        if (is_resource($handle)) {
            $this->handle = $handle;
        }
    }
    
    
    public function log($level, $message, array $context = [])
    {
        if ($this->handle) {
            fwrite($this->handle, $level . ': ' . $message . print_r($context, true) . PHP_EOL . PHP_EOL);
        }
    }
    
    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }
    
}