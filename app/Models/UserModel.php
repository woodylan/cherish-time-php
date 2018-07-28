<?php

namespace App\Models;


use App\Models\Base\BaseModel;
use App\Tools\ArrayTool;

class UserModel extends BaseModel
{
    protected $table = 'cherish_time.tb_user';
    protected $fillable = [
    ];

    protected $hidden = [
    ];

    protected $casts = [
    ];

    public function add(array $inputData, array $adminUser = [])
    {
        //step.1 填充字段
        //可以使用以下两种方式，--任选一种--
        //但是一定要慎重，不能以方便为第一选择依据，一定要以合理的系统设计为第一准则
        //method.1 当传入字段完全可以成为数据库字段时
        //添加可填充的字段
        $this->fillable([
            'title',
            'author',
            'content',
        ]);
        //填充字段
        $this->fill(ArrayTool::snakeCaseArray($inputData, false));

        //method.2 当传入字段与最外层不一致时
        $this->title = $inputData['title_test'] ?? '';//加入传入的是title_test

        //step.2 如果有记录管理后台人员，则在这里显式的进行保存处理，因为不一定所有的model都有
        $this->fillOperationUser($adminUser);

        //step.3 save
        $this->save();
    }

    //与add基本一致，但是最好写两个，为了以后扩展容易
    public function edit(array $inputData, array $adminUser = [])
    {
        //step.1 填充字段
        //可以使用以下两种方式，任选一种
        //但是一定要慎重，不能以方便为第一选择依据，一定要以合理的系统设计为第一准则
        //method.1 当传入字段完全可以成为数据库字段时
        //添加可填充的字段
        $this->fillable([
            'title',
            'author',
            'content',
        ]);
        //填充字段
        $this->fill(ArrayTool::snakeCaseArray($inputData, false));

        //method.2 当传入字段与最外层不一致时
        $this->title = $inputData['title_test'] ?? '';//加入传入的是title_test

        //step.2 如果有记录管理后台人员，则在这里显式的进行保存处理，因为不一定所有的model都有
        $this->fillOperationUser($adminUser);

        //step.3 save
        $this->save();
    }

    //个别字段需要进行单独，或者是有一定复杂性或者需要联通修改其他字段时，使用单独的方法进行处理
    public function setTitle($title)
    {
        //some complex stored procedure
        $this->title = $title;
        $this->save();
    }

    //这里是对数据取出时进行的简单逻辑处理办法，getxxxxAttribute()
    public function getContentAttribute($value)
    {
        return explode("\n", $value);
    }

    public function getList($condition, $currentPage, $perPage)
    {
        $model = self::query();

        if (isset($condition['author'])) {
            $model->where('authod', $condition['author']);
        }
        $model->orderBy('create_time', 'desc');

        //分页组件
        $modelDatas = $model->paginate($perPage, ['*'], 'page', $currentPage);

        return $modelDatas;
    }

    public function getByOpenId($openId)
    {
        return self::where('open_id', $openId)
            ->first();
    }

    public function updateUserInfo($nickName, $gender, $city, $province, $country)
    {
        $this->nick_name = $nickName;
        $this->sex = $gender;
        $this->city = $city;
        $this->province = $province;
        $this->country = $country;

        $this->save();

        return $this;
    }

    public function addNew($openId, $nickName, $sex, $city, $province, $country, $avatar)
    {
        $this->open_id = $openId;
        $this->nick_name = $nickName;
        $this->sex = $sex;
        $this->city = $city;
        $this->province = $province;
        $this->country = $country;
        $this->avatar = $avatar;
        $this->save();

        return $this;
    }
}