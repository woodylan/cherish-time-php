<?php

namespace App\Models\Base;

use App\Models\Base\BaseModel;
use App\Tools\StringTool;

class BaseModelObserver
{
    /**
     * 监听用户创建的事件
     * @param BaseModel $model
     */
    public function creating(BaseModel $model)
    {
        if (strlen($model->getKey()) < 1) {
            //自动填充主键的值
            $model->setAttribute($model->getKeyName(), StringTool::md5Uuid());
        }
    }
}
