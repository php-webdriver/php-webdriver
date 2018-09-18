<?php

namespace Facebook\WebDriver\Remote;

class WebDriverDialect
{
    const W3C_DIALECT = 'W3C';
    const JSON_WIRE_PROTOCOL_DIALECT = 'OSS';

    /** @var string */
    private $dialect;

    /**
     * WebDriverDialect constructor.
     * @param string $dialect
     */
    private function __construct($dialect)
    {
        $this->dialect = $dialect;
    }

    /**
     * @return WebDriverDialect
     */
    public static function createW3C()
    {
        return new self(self::W3C_DIALECT);
    }

    /**
     * @return WebDriverDialect
     */
    public static function createJsonWireProtocol()
    {
        return new self(self::JSON_WIRE_PROTOCOL_DIALECT);
    }

    /**
     * @return bool
     */
    public function isW3C()
    {
        return $this->dialect === self::W3C_DIALECT;
    }

    /**
     * @param array $result
     * @return WebDriverDialect
     */
    public static function guessByNewSessionResultBody(array $result)
    {
        if (!isset($result['status'])) {
            return self::createW3C();
        }
        return self::createJsonWireProtocol();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dialect;
    }
}
