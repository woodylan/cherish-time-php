<?php

namespace App\Http\Controllers\Admin\Order;

use App\Define\Common;
use App\Formatter\OrderFormatter;
use App\Http\Controllers\Controller;
use App\Logic\Admin\Order\AdminOrderLogic;

class GetDetail extends Controller
{
    /**
     * @api {post} /api/admin/v1/order/detail 详情
     * @apiGroup 分组名称
     *
     * @apiParam {String} data 数据
     *
     *
     * @apiParamExample {json} 请求示例:
     * {"id":"002e2746ef211be0"}
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{"id":"002e2746ef211be0","title":"\u591c\u7b1b\u8a5e","author":"\u65bd\u80a9\u543e","content":["\u768e\u6d01\u897f\u697c\u6708\u672a\u659c\uff0c\u7b1b\u58f0\u5be5\u4eae\u5165\u4e1c\u5bb6\u3002","\u5374\u4ee4\u706f\u4e0b\u88c1\u8863\u5987\uff0c\u8bef\u7fe6\u540c\u5fc3\u4e00\u534a\u82b1\u3002"]}}
     */
    public function run()
    {
        $id = $this->input('id');

        $courseLogic = new AdminOrderLogic($this->getAdminUser());

        $ret = $courseLogic->getDetail($id, [
            new OrderFormatter(),
            'adminDetailFormat'
        ]);

        return $this->render(Common::SUCCESS, 'success', $ret);
    }

    public function rules()
    {
        return [
            'id' => ['required|min:3|max:32', 'ID'],
        ];
    }
}