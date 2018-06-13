<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\Common;
use App\Formatter\TimeFormatter;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\TimeLogic;

class GetDetail extends Controller
{
    /**
     * @api               {post} /api/weapp/v1/time/detail 时间详情
     * @apiGroup          TIME
     *
     * @apiParamExample {json} 请求示例:
     * {"id":"6efb11e8842885db"}
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{"id":"6efb11e8842885db","type":2,"color":"#ffff","date":20180614,"remark":"\u6d4b\u8bd5\u65f6\u95f4\u54c811","createTime":1528888542}}
     */
    public function run()
    {
        $id = $this->input('id');

        $courseLogic = new TimeLogic($this->getUser());

        $ret = $courseLogic->getDetail($id, [
            new TimeFormatter(),
            'userDetailFormat'
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