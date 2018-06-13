<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\Common;
use App\Formatter\TimeFormatter;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\TimeLogic;

class GetList extends Controller
{
    /**
     * @api               {post} /api/weapp/v1/time/list 时间列表
     * @apiGroup          TIME
     *
     * @apiParamExample {json} 请求示例:
     * {"currentPage":1,"perPage":10}
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{"count":2,"perPage":10,"currentPage":1,"lastPage":1,"list":[{"id":"6efd11e8838a8980","type":1,"color":"#e84e40","date":20181001,"remark":"\u65c5\u6e38\u53bb\u54af","createTime":1528889544},{"id":"6efb11e8842885db","type":2,"color":"#ffff","date":20180614,"remark":"\u6d4b\u8bd5\u65f6\u95f4\u54c811","createTime":1528888542}]}}
     */
    public function run()
    {
        $currentPage = $this->input('currentPage', 1);
        $perPage = $this->input('perPage', 20);
        $condition = $this->only(['perPage', 'currentPage']);

        $logic = new TimeLogic($this->getUser());
        $ret = $logic->getList($currentPage, $perPage, $condition, [
            new TimeFormatter(),
            'userListFormat',
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