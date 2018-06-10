<?php
/**
 * Created by PhpStorm.
 * User: johnson
 * Date: 2018/5/6
 * Time: 上午12:01
 */

namespace App\Tools;


class Config
{
    private static $_selfConfigArray = [];

    public static function envConfig($name, $default = null)
    {
        $env = app()->environment();
        $env = in_array($env, ['local', 'self']) ? 'local' : $env;
        app()->configure(sprintf("%s/%s", $env, current(explode('.', $name))));
        return config(sprintf("%s/%s", $env, $name), $default);
    }

    public static function getSelfConfig($key)
    {
        if (isset(self::$_selfConfigArray[$key])) {
            return self::$_selfConfigArray[$key];
        }
        return '';
    }

    public static function setSelfConfig($key, $value)
    {
        self::$_selfConfigArray[$key] = $value;
    }
}