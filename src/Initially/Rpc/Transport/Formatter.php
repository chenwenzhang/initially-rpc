<?php
namespace Initially\Rpc\Transport;

class Formatter
{

    /**
     * @param mixed $value
     * @return string
     */
    public static function serialize($value)
    {
        return serialize($value);
    }

    /**
     * @param string $string
     * @return mixed
     */
    public static function unserialize($string)
    {
        return unserialize($string);
    }

}