<?php

namespace App\Formatter;


class OrderFormatter
{
    public function adminListFormat($class, $batchData = [])
    {
        //$batchData 为批量获取的数据，通过 getListBatchData 方法获得，这样你就不用多次获取数据，可以一次性获取，在这里取得一条数据

        return [
            'id'      => $class->id,
            'title'   => $class->title,
            'author'  => $class->author,
            'content' => $class->content,
        ];
    }

    public function adminDetailFormat($class)
    {
        return [
            'id'      => $class->id,
            'title'   => $class->title,
            'author'  => $class->author,
            'content' => $class->content,
        ];
    }

    public function userListFormat($class, $batchData = [])
    {
        return [
            'id'      => $class->id,
            'title'   => $class->title,
            'author'  => $class->author,
            'content' => $class->content,
        ];
    }

    public function userDetailFormat($class)
    {
        return [
            'id'      => $class->id,
            'title'   => $class->title,
            'author'  => $class->author,
            'content' => $class->content,
        ];
    }
}