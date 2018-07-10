<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\TimeLogic;

/**
 * @apiDefine TIME 时间
 *            时间相关接口
 */
class Create extends Controller
{
    /**
     * @api               {post} /api/weapp/v1/time/create 新建时间
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
        $inputData = $this->only(['name', 'type', 'color', 'date', 'remark']);

        $logic = new TimeLogic($this->getUser());
        $logic->create($inputData);

        return $this->render(RetCode::SUCCESS, 'success');
    }

    public function rules()
    {
        return [
            'name'   => ['required', '名称'],
            'type'   => ['required|integer|between:1,2', '类型'],
            'color'  => ['required|array', '颜色'],
            'date'   => ['required|integer', '日期'],
            'remark' => ['', '备注'],
        ];
    }
}