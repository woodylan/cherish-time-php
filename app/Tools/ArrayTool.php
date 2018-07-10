<?php

namespace App\Tools;

use Illuminate\Pagination\AbstractPaginator;


class ArrayTool
{
    public static function modelToArray($row, $formatter = null)
    {
        if ($formatter) {
            $moreArgs = [];
            if (is_array($formatter) && count($formatter) == 3) {
                $moreArgs = array_pop($formatter);
                $moreArgs = array_shift($moreArgs);
            }
            $moreArgs[0] = $row;
            return call_user_func_array($formatter, $moreArgs);
        } else {
            return static::camelCaseArray($row->toArray());
        }
    }

    public static function emptyArrayList($pager = true, $perPage = 10, $currentPage = 1, $lastPage = 0)
    {
        if ($pager) {
            return [
                'count'       => 0,
                'perPage'     => $perPage,
                'currentPage' => $currentPage,
                'lastPage'    => $lastPage,
                'list'        => [],
            ];
        }
        return [];
    }

    /**
     * 把 model 列表数据格式化
     *
     * @param       $data
     * @param null  $formatter
     * @param array $batchData
     *
     * @return array
     */
    public static function modelListToArray($data, $formatter = null, array $batchData = [])
    {
        $list = [];
        $rows = ($data instanceof AbstractPaginator) ? $data->getCollection() : $data;

        foreach ($rows as $i => $row) {
            if ($formatter) {
                $list[$i] = call_user_func_array($formatter, [$row, $batchData]);
            } else {
                $list[$i] = static::camelCaseArray($row->toArray());
            }
        }

        if ($data instanceof AbstractPaginator) {
            return [
                'count'       => $data->total(),
                'perPage'     => intval($data->perPage()),
                'currentPage' => $data->currentPage(),
                'lastPage'    => $data->lastPage(),
                'list'        => $list,
            ];
        } else {
            return $list;
        }
    }

    /**
     * 将数组的键名首字母小写
     *
     * @param array|string $data      数据
     * @param bool         $recursion 是否转换多维数组里的键名
     * @param bool         $trim      是否需要进行首尾" \t\n\r\0\x0B"的裁剪
     *
     * @return array
     */
    public static function lcFirstArray($data, $recursion = true, $trim = false)
    {
        if (!is_array($data)) {
            return $data;
        }
        $arr = [];
        foreach ($data as $key => $val) {
            $key = lcfirst($key);

            //多维数组也转
            if ($recursion && is_array($val)) {
                $val = static::lcFirstArray($val, $recursion);
            }
            if ($trim) {
                $arr[$key] = is_string($val) ? trim($val) : $val;
            } else {
                $arr[$key] = $val;
            }
        }

        return $arr;
    }

    /**
     * 键名驼峰式大小写
     *
     * @param array $data      数据
     * @param bool  $ucFirst   首字母是否大小写
     * @param bool  $recursion 是否转换多维数组里的键名
     *
     * @return array
     */
    public static function camelCaseArray($data, $ucFirst = false, $recursion = true)
    {
        $arr = [];
        foreach ($data as $key => $val) {
            $key = $ucFirst ? ucfirst(camel_case($key)) : camel_case($key);
            if ($recursion && is_array($val)) {
                $val = static::camelCaseArray($val, $ucFirst);
            }
            $arr[$key] = $val;
        }
        return $arr;
    }

    /**
     * 复合词用下划线的命名法
     *
     * @param      $data
     * @param bool $recursion
     *
     * @return array
     */
    public static function snakeCaseArray($data, $recursion = true)
    {
        $arr = [];
        foreach ($data as $key => $val) {
            $key = snake_case($key);
            if ($recursion && is_array($val)) {
                $val = static::snakeCaseArray($val);
            }
            $arr[$key] = $val;

        }
        return $arr;
    }

    /**
     * 判断数组里的各个值在不在数组里
     *
     * @param array $list
     * @param array $itemList
     *
     * @return bool
     */
    public static function isInArray(array $list, array $itemList): bool
    {
        if (empty($list)) {
            return true;
        }

        foreach ($list as $value) {
            if (!in_array($value, $itemList)) {
                return false;
            }
        }

        return true;
    }


}