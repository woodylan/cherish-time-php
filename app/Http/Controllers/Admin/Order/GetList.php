<?php

namespace App\Http\Controllers\Admin\Order;

use App\Define\Common;
use App\Formatter\OrderFormatter;
use App\Http\Controllers\Controller;
use App\Logic\Admin\Order\AdminOrderLogic;

class GetList extends Controller
{
    /**
     * @api {post} /api/admin/v1/order/list 列表
     * @apiGroup 分组名称
     *
     * @apiParam {String} data 数据
     *
     *
     * @apiParamExample {json} 请求示例:
     * {"perPage":2,"currentPage":1}
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{"count":5630,"perPage":2,"currentPage":1,"lastPage":2815,"list":[{"id":"C80BFCD00C2BE9F1","title":"\u9ec4\u9e64\u697c\u9001\u5b5f\u6d69\u7136\u4e4b\u5e7f\u96751","author":"\u674e\u767d1","content":["111\u6545\u4eba\u897f\u8f9e\u9ec4\u9e64\u697c\uff0c\u70df\u82b1\u4e09\u6708\u4e0b\u626c\u5dde\u3002\u5b64\u5e06\u8fdc\u5f71\u78a7\u7a7a\u5c3d\uff0c\u552f\u89c1\u957f\u6c5f\u5929\u9645\u6d41\u3002 "]},{"id":"ffec8a92d743be07","title":"\u904e\u9152\u5bb6\u4e94\u9996 \u4e09","author":"\u738b\u7e3e","content":["\u7af9\u53f6\u8fde\u7cdf\u7fe0\uff0c\u84b2\u8404\u5e26\u66f2\u7ea2\u3002","\u76f8\u9022\u4e0d\u4ee4\u5c3d\uff0c\u522b\u540e\u4e3a\u8c01\u7a7a\u3002"]}]}}
     */
    public function run()
    {
        //所有参数都在这列出吧，方便一眼看出有哪些参数
        $currentPage = $this->input('currentPage', 1);//不是必填的参数给一个默认的值
        $perPage = $this->input('perPage', 20);
        $condition = $this->only(['perPage', 'currentPage']);

        $logic = new AdminOrderLogic($this->getAdminUser());
        $ret = $logic->getList($currentPage, $perPage, $condition, [
            new OrderFormatter(),
            'adminListFormat',
        ]);

        return $this->render(Common::SUCCESS, 'success', $ret);
    }

    public function rules()
    {
        return [
            'perPage'     => ['min:1', '每页记录数'],
            'currentPage' => ['min:1', '页码'],
        ];
    }
}