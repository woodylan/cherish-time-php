<?php
namespace App\Tools;

class StringTool
{
    public static function numberId()
    {
        static $exists;
        if (!$exists) {
            $exists = [];
        }
        do {
            $days = intval(time() / 86400);
            $secs = time() - $days * 86400;
            $machineCode = sprintf("%02d", config('app.machine.code', 1));
            $num = mt_rand(1, 9999);
            $id = sprintf("%05d%01d%05d%01d%04d", $days, $machineCode {
                0}, $secs, $machineCode {
                1}, $num);
        } while (isset($exists[$id]));
        $exists[$id] = true;
        return $id;
    }

    public static function uuid()
    {
        if (function_exists('uuid_create')) {
            return str_replace('-', '', uuid_create());
        } else {
            return str_replace('.', '', uniqid('2', true));
        }
    }

    public static function createUuid($short = true)
    {
        static $exists = [];
        do {
            $md5 = md5(self::uuid());
            if ($short) {
                $md5 = substr($md5, 8, 16);
            }
        } while (isset($exists[$md5]));
        $exists[$md5] = true;
        return strtoupper($md5);
    }

    public static function fenToYuan($fen)
    {
        $sign = $fen >= 0 ? '' : '-';
        $fen = abs($fen);
        if ($fen % 100 !== 0) {
            return floatval(sprintf("%s%d.%02d", $sign, intval($fen / 100), $fen % 100));
        } else {
            return intval(sprintf("%s%d", $sign, $fen / 100));
        }
    }
}