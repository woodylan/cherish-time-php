<?php

namespace App\Tools;

use Webpatser\Uuid\Uuid;

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
            $id = sprintf("%05d%01d%05d%01d%04d", $days, $machineCode{
            0}, $secs, $machineCode{
            1}, $num);
        } while (isset($exists[$id]));
        $exists[$id] = true;
        return $id;
    }

    public static function createUuid($short = true)
    {
        $uuid = str_replace('-', '', Uuid::generate()->string);
        if ($short) {
            $uuid = substr($uuid, 8, 16);
        }

        return $uuid;
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