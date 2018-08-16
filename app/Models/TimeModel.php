<?php

namespace App\Models;


use App\Models\Base\BaseModel;
use App\Tools\ArrayTool;

class TimeModel extends BaseModel
{
    protected $table = 'cherish_time.tb_time';
    protected $fillable = [
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'type'  => 'integer',
        'date'  => 'integer',
        'color' => 'array',
    ];

    public function add(array $inputData, UserModel $user = null)
    {
        $this->fillable([
            'name',
            'date',
            'type',
            'color',
            'remark',
            'user_id'
        ]);

        //填充字段
        $this->fill(ArrayTool::snakeCaseArray($inputData, false));
        $this->fillOperationUser($user);

        $this->save();
    }

    //与add基本一致，但是最好写两个，为了以后扩展容易
    public function edit(array $inputData, UserModel $user = null)
    {
        //添加可填充的字段
        $this->fillable([
            'name',
            'date',
            'color',
            'remark',
            'user_id'
        ]);
        $this->fill(ArrayTool::snakeCaseArray($inputData, false));
        $this->fillOperationUser($user);

        $this->save();

        return $this;
    }

    public function getContentAttribute($value)
    {
        return explode("\n", $value);
    }

    public function getList($condition, $currentPage, $perPage)
    {
        $model = self::query();

        $model->where('user_id', $condition['userId']);
        $model->orderBy('create_time', 'desc');

        //分页组件
        $modelDatas = $model->paginate($perPage, ['*'], 'page', $currentPage);

        return $modelDatas;
    }
}