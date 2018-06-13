<?php

namespace App\Formatter;


class TimeFormatter
{
    public function userListFormat($class)
    {
        return [
            'id'         => $class->id,
            'type'       => $class->type,
            'color'      => $class->color,
            'date'       => $class->date,
            'remark'     => $class->remark,
            'createTime' => $class->create_time->timestamp,
        ];
    }

    public function userDetailFormat($class)
    {
        return [
            'id'         => $class->id,
            'type'       => $class->type,
            'color'      => $class->color,
            'date'       => $class->date,
            'remark'     => $class->remark,
            'createTime' => $class->create_time->timestamp,
        ];
    }
}