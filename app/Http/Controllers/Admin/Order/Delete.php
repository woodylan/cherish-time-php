<?php

namespace App\Http\Controllers\Admin\Order;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Admin\Order\AdminOrderLogic;

class Delete extends Controller
{
    /**
     * @api {post} /api/admin/v1/order/delete 删除
     * @apiGroup 分组名称
     *
     * @apiParam {String} data 数据
     *
     *
     * @apiParamExample {json} 请求示例:
     * {"id":"002e2746ef211be0"}
     *
     * @apiSuccessExample Success-Response:
     * {"code": 0,"msg": "SUCCESS","data": {}}
     */
    public function run()
    {
        $id = $this->input('id');

        $logic = new AdminOrderLogic($this->getAdminUser());
        $logic->delete($id);

        return $this->render(RetCode::SUCCESS, 'success');
    }

    public function rules()
    {
        return [
            'id' => ['required|min:3|max:32', 'ID'],
        ];
    }
}