<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\TimeLogic;

class Edit extends Controller
{
    /**
     * @api               {post} /api/weapp/v1/time/edit 编辑时间
     * @apiGroup          TIME
     *
     * @apiParamExample {json} 请求示例:
     * {"id":"11111","name":"国庆","type":1,"color":"#e84e40","date":20181001,"remark":"旅游去咯"}
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{}}
     */
    public function run()
    {
        $inputData = $this->only(['id', 'name', 'type', 'color', 'date', 'remark']);

        $logic = new TimeLogic($this->getUser());
        $logic->edit($inputData);

        return $this->render(RetCode::SUCCESS, 'success');
    }

    public function rules()
    {
        return [
            'id'     => ['required|min:16|max:32', 'ID'],
            'name'   => ['required|max:10', '名称'],
            'type'   => ['required|integer|between:1,2', '类型'],
            'color'  => ['required', '颜色'],
            'date'   => ['required|integer', '日期'],
            'remark' => ['max:13', '备注'],
        ];
    }
}