<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\TimeLogic;

class Delete extends Controller
{
    /**
     * @api               {post} /api/weapp/v1/time/delete 删除时间
     * @apiGroup          TIME
     *
     * @apiParamExample {json} 请求示例:
     * {"name":"国庆","type":1,"color":"#e84e40","date":20181001,"remark":"旅游去咯"}
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{}}
     */
    public function run()
    {
        $id = $this->input('id');

        $logic = new TimeLogic($this->getUser());
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