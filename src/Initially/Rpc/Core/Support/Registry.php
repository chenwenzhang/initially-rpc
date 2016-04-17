<?php
namespace Initially\Rpc\Core\Support;

class Registry
{

    /**
     * @var array
     */
    private static $container = array();

    /**
     * @param string $key
     * @param callable|mixed $func
     */
    public static function set($key, $func)
    {
        self::$container[$key] = $func;
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public static function get($key)
    {
        if (!isset(self::$container[$key])) {
            return null;
        } elseif (is_callable(self::$container[$key])) {
            self::$container[$key] = call_user_func(self::$container[$key]);
        }
        return self::$container[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset(self::$container[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function del($key)
    {
        if (self::has($key)) {
            unset(self::$container[$key]);
        }
        return true;
    }

}