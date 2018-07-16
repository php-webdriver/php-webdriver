<?php

namespace Facebook\WebDriver\Remote;

/**
 * Class ExecutableWebDriverCommand
 * @package Facebook\WebDriver\Remote
 */
class ExecutableWebDriverCommand
{
    
    /**
     * @var string
     */
    private $url;
    
    /**
     * @var string
     */
    private $method;
    
    /**
     * @var WebDriverCommand
     */
    private $command;
    
    /**
     * @var WebDriverDialect
     */
    private $dialect;
    
    /**
     * ExecutableWebDriverCommand constructor.
     * @param string $url
     * @param string $method
     * @param WebDriverCommand $command
     * @param WebDriverDialect|null $dialect
     */
    public function __construct($url, $method, WebDriverCommand $command, WebDriverDialect $dialect = null)
    {
        $this->url = $url;
        $this->method = $method;
        $this->command = $command;
        $this->dialect = $dialect;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->command->getName();
    }
    
    /**
     * @return string
     */
    public function getSessionID()
    {
        return $this->command->getSessionID();
    }
    
    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->command->getParameters();
    }
    
    /**
     * @return WebDriverDialect
     */
    public function getDialect()
    {
        return $this->dialect;
    }
}