<?php

namespace App\Http\Controllers\Mobile\Order;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Order\OrderLogic;

class Create extends Controller
{
    /**
     * @api {post} /api/mobile/v1/order/create 新建
     * @apiGroup 分组名称
     *
     * @apiParam {String} data 数据
     *
     *
     * @apiParamExample {json} 请求示例:
     * {"title":"黄鹤楼送孟浩然之广陵","author":"李白","content":"故人西辞黄鹤楼，烟花三月下扬州。孤帆远影碧空尽，唯见长江天际流。"}
     *
     * @apiSuccessExample Success-Response:
     * {"code": 0,"msg": "SUCCESS","data": {}}
     */
    public function run()
    {
        $inputData = $this->only(['title', 'author', 'content']);

        $logic = new OrderLogic($this->getUser());
        $logic->create($inputData);

        return $this->render(RetCode::SUCCESS, 'success');
    }

    public function rules()
    {
        return [
            'title'   => ['required', '标题'],
            'author'  => ['required', '作者'],
            'content' => ['required', '内容']
        ];
    }
}