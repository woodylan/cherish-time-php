<?php

namespace App\Formatter;


class UserFormatter
{
    public function userDetailFormat($class)
    {
        return [
            'userId'   => $class->use_id,
            'openId'   => $class->open_id,
            'name'     => $class->nick_name,
            'avatar'   => $class->avatar,
            'sex'      => $class->sex,
            'city'     => $class->city,
            'province' => $class->province,
            'country'  => $class->country,
        ];
    }
}