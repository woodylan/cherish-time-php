<?php

namespace App\Logic\Admin\Order;

use App\Define\RetCode;
use App\Logic\Admin\AdminBaseLogic;
use App\Models\OrderModel;
use App\Tools\ArrayTool;
use App\Tools\StringTool;
use App\Tools\Util;

class AdminOrderLogic extends AdminBaseLogic
{
    public function create(array $inputData)
    {
        //参数校验
        if (!$this->_validateEditParam($inputData)) {
            Util::errorCode(RetCode::PARAM_ERROR);
        }

        $orderModel = new OrderModel(StringTool::createUuid());

        \DB::beginTransaction();
        //before save
        $orderModel->add($inputData, $this->user);
        //after save
        \DB::commit();
    }

    public function edit(array $inputData)
    {
        //参数校验
        if (!$this->_validateEditParam($inputData)) {
            Util::errorCode(RetCode::PARAM_ERROR);
        }

        $id = $inputData['id'];
        $orderModel = new OrderModel($id);
        if (!$orderModel->exists) {
            Util::errorCode(RetCode::ERR_OBJECT_NOT_FOUND);
        }
        \DB::beginTransaction();

        //before save
        $orderModel->edit($inputData, $this->user);
        //after save
        \DB::commit();
    }

    private function _validateEditParam(array $inputData)
    {
        //do some validate logic and return true or false
        return true;
    }

    public function getList(int $currentPage, int $perPage, array $condition = [], $format = null): array
    {
        $model = new OrderModel();
        $models = $model->getList($condition, $currentPage, $perPage);

        //整合批量数据，用于将需要用到的方法透传到modelListToArray内部，共函数内部调用使用
        //同时，我们希望batchData是一个map结构，这样可以看起来更规范
        $batchData = [];
        $batchData['userMapList'] = [
            'bob' => [
                'id'  => 'bob',
                'age' => 15,
            ],
        ];

        return ArrayTool::modelListToArray($models, $format, $batchData);
    }

    public function getDetail(string $id, $format = null): array
    {
        $orderModel = new OrderModel($id);
        if (!$orderModel->exists) {
            Util::errorCode(RetCode::ERR_OBJECT_NOT_FOUND);
        }

        return ArrayTool::modelToArray($orderModel, $format);
    }

    public function delete(string $id)
    {
        $orderModel = new OrderModel($id);
        if (!$orderModel->exists) {
            Util::errorCode(RetCode::ERR_OBJECT_NOT_FOUND);
        }

        \DB::beginTransaction();
        //before delete
        $orderModel->delete();
        //after delete
        \DB::commit();
    }
}