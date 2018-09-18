<?php

namespace Facebook\WebDriver\Support;

use Facebook\WebDriver\WebDriverKeys;

class W3CKeysEncoder
{
    public static function encode($value)
    {
        return self::doEncode((array) $value);
    }

    /**
     * @param array $value
     * @return array
     */
    private static function doEncode(array $value)
    {
        $typing = [];
        foreach ($value as $val) {
            if (WebDriverKeys::isSpecialKey($val)) {
                $typing[] = $val;
                continue;
            }
            if (is_array($val)) {
                $typing = array_merge($typing, self::doEncode($val));
                continue;
            }
            $typing = array_merge($typing, str_split((string) $val));
        }
        return $typing;
    }
}