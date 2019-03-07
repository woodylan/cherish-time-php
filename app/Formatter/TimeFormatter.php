<?php

namespace App\Formatter;


use App\Define\Common;
use Carbon\Carbon;

class TimeFormatter
{
    public function userListFormat($class, $sentenceModelArray)
    {
        $nowDate = (int)date('Ymd', time());
        //倒计时
        if ($class->type == Common::TIME_TYPE_DESC) {
            $days = self::daysDiff($nowDate, $class->date);
        } elseif ($class->type == Common::TIME_TYPE_ASC) {
            //正计时
            $days = self::daysDiff($class->date, $nowDate);
        }

        $sentenceModel = $sentenceModelArray[$class->serial];

        $sentence = [
            'id'      => $sentenceModel['id'],
            'content' => $sentenceModel['content'],
            'author'  => $sentenceModel['author'],
            'book'    => $sentenceModel['book'],
        ];

        return [
            'id'         => $class->id,
            'name'       => $class->name,
            'type'       => $class->type,
            'color'      => $class->color,
            'date'       => $class->date,
            'days'       => $days,
            'remark'     => $class->remark,
            'sentence'   => $sentence,
            'createTime' => $class->created_at->timestamp,
        ];
    }

    public function userDetailFormat($class)
    {
        $nowDate = date('Ymd', time());
        $days = 0;
        //倒计时
        if ($class->type == Common::TIME_TYPE_DESC) {
            $days = self::daysDiff($nowDate, $class->date);
        } elseif ($class->type == Common::TIME_TYPE_ASC) {
            //正计时
            $days = self::daysDiff($class->date, $nowDate);
        }

        return [
            'id'         => $class->id,
            'name'       => $class->name,
            'type'       => $class->type,
            'color'      => $class->color,
            'date'       => $class->date,
            'days'       => $days,
            'remark'     => $class->remark,
            'createTime' => $class->created_at->timestamp,
        ];
    }

    private static function daysDiff($startTime, $endTime)
    {
        $startTime = new Carbon($startTime);
        $endTime = new Carbon($endTime);

        if ($startTime > $endTime) {
            return -1;
        }

        return $endTime->diffInDays($startTime);
    }
}