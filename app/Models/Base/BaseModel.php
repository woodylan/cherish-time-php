<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'deleted_at';

    public $timestamps = true;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    /**
     * 主键是否自增
     *
     * @var string
     */
    public $incrementing = false;

    /**
     * 模型的日期字段的存储格式
     * 格式可参考 http://php.net/manual/zh/function.date.php
     *
     * @var string
     */
    protected $dateFormat = 'U';

    protected $arrayFormatter;

    //重写model基类的构造函数，使可以方便的创建model对象
    public function __construct($id = null)
    {
        parent::__construct();
        if (!is_null($id)) {
            $model = self::find($id);
            if ($model) {
                foreach ($model as $key => $value) {
                    $this->$key = $value;
                }
            } else {
                $this->id = $id;
            }
        }
    }

    protected static function boot()
    {
        parent::boot();
        //注册模型基础观察器
        static::observe(BaseModelObserver::class);
    }

    /**
     * 填充允许修改的字段
     *
     * @param      $data
     * @param null $fillable
     * @param null $dirty
     */
    public function fillAndSave($data, $fillable = null, &$dirty = null)
    {
        if ($fillable) {
            $this->fillable($fillable);
        }
        $this->fill($data);
        $dirty = $this->getDirty();
        $this->save();
    }

    /*
     * 查出来的数据根据指定字段转换成map，k => v结构
     * eg.
     * $datas = [
     *     'zhangdaqian' => [
     *         'userId' => 'zhangdaqian',
     *         'age'    => 15,
     *     ]
     * ];
     */
    public static function toMap($field, $models)
    {
        //如果不是数组直接扔回去
        if (!is_array($models)) {
            return $models;
        }

        $mapDatas = [];
        foreach ($models as $data) {
            $key = '';
            if (is_array($data)) {
                $key = $data[$field];
            }
            if (is_object($data)) {
                $key = $data->$field;
            }
            $mapDatas[$key] = $data;
        }

        return $mapDatas;
    }

    /*
     * 与toMap的区别：根据某字段聚合成groupMap，里面数据是list
     * eg.
     * $datas = [
     *     'zhangdaqian' => [
     *         [
     *             'userId' => 'zhangdaqian',
     *             'age'    => 15,
     *         ]
     *     ]
     * ];
     */
    public static function toGroupMap($field, $models)
    {
        //如果不是数组直接扔回去
        if (!is_array($models)) {
            return $models;
        }

        $mapGroupDatas = [];
        foreach ($models as $data) {
            $key = '';
            if (is_array($data)) {
                $key = $data[$field];
            }
            if (is_object($data)) {
                $key = $data->$field;
            }

            $mapGroupDatas[$key][] = $data;
        }

        return $mapGroupDatas;
    }

    /*
     * getByField，可以传递字段和对应的值，进行查询
     *
     * @param string fieldName
     * @param string fieldValue
     * @param bool   onlyOne 只查询唯一的一条，此时返回对象
     * @param bool   toMap/toArray 当onlyOne为真时，其实是将唯一的orm对象转换成数组
     * @param bool   filterDeleted
     *
     * @return array
     */
    public static function getByField($fieldName, $fieldValue, $onlyOne = true, $toMapArray = false, $filterDeleted = true)
    {
        if (empty($fieldName)) {
            return [];
        }
        $model = self::where($fieldName, $fieldValue);

        if ($filterDeleted) {
            $model = $model->whereNotNull('deleted_at');
        }

        if ($onlyOne) {
            $modelData = $model->first();
            if ($toMapArray) {
                return $modelData->toArray();
            } else {
                return $modelData;
            }
        } else {
            $modelData = $model->get();
            if ($toMapArray) {
                return self::toMap($fieldName, $modelData);
            } else {
                return $modelData;
            }
        }
    }

    /*
     * getByFields，可以传递字段和对应的数组值，进行批量查询
     *
     * @param string fieldName
     * @param array  fields
     * @param bool   toMap
     * @param bool   filterDeleted
     *
     * @return array
     */
    public static function getByFields($fieldName, array $fields, $toMap = false, $filterDeleted = true)
    {
        if (empty($fields) || empty($fieldName)) {
            return [];
        }
        $model = self::whereIn($fieldName, $fields);

        if ($filterDeleted) {
            $model = $model->whereNotNull('deleted_at');
        }

        $datas = $model->get();

        if ($toMap) {
            return self::toMap($fieldName, $datas);
        } else {
            return $datas;
        }
    }

    /**
     * 填充操作人、操作时间
     *
     * @param $row
     */
    protected function fillOperationUser($user)
    {
        if (is_null($user)) {
            return;
        }
        $this->update_user_id = $user->id ?? '';
        if (!$this->exists) {
            $this->create_user_id = $user->id ?? '';
        }
    }
}
