<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\WebDriverException;

class WebDriverResponseFactory
{
    /**
     * @param WebDriverDialect $dialect
     * @param mixed $results
     * @return WebDriverResponse
     * @throws WebDriverException
     */
    public static function createByDialect(WebDriverDialect $dialect, $results)
    {
        return $dialect->isW3C()
            ? self::createW3CProtocol($results)
            : self::createJsonWireProtocol($results);
    }
    
    /**
     * @param mixed $results
     * @return WebDriverResponse
     * @throws WebDriverException
     */
    private static function createJsonWireProtocol($results)
    {
        if (!isset($results['status'])) {
            return null;
        }
        
        $value = null;
        if (is_array($results) && array_key_exists('value', $results)) {
            $value = $results['value'];
        }
        
        $sessionId = null;
        if (is_array($results) && array_key_exists('sessionId', $results)) {
            $sessionId = $results['sessionId'];
        }
    
        $status = $results['status'];
        if ($status !== 0) {
            $message = null;
            if (is_array($value) && array_key_exists('message', $value)) {
                $message = $value['message'];
            }
            WebDriverException::throwException($status, $message, $results);
        }
        
        $response = new WebDriverResponse($sessionId);
    
        return $response
            ->setStatus($status)
            ->setValue($value);
    }
    
    /**
     * @param mixed $results
     * @return WebDriverResponse
     * @throws WebDriverException
     */
    private static function createW3CProtocol($results)
    {
        $value = $results['value'];
    
        $sessionId = null;
        if (is_array($value)) {
            if (!empty($value['sessionId'])) {
                $sessionId = $value['sessionId'];
                unset($value['sessionId']);
                
            } elseif (!empty($results['sessionId'])) {
                $sessionId = $results['sessionId'];
            }
    
            if (!empty($value['capabilities'])) {
                $value = $value['capabilities'];
            } elseif (null !== $sessionId && empty($value['moz:profile'])) {
                return null;
            }
        }
        
        if (!empty($value['error'])) {
            WebDriverException::throwExceptionForW3c($value['error'], $results);
        }
    
        $response = new WebDriverResponse($sessionId);
        
        return $response
            ->setValue($value);
    }
}