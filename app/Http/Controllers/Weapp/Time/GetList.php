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
     * {"code":0,"msg":"success","data":{"count":14,"perPage":2,"currentPage":1,"lastPage":7,"list":[{"id":"867711e8ae2f2db6","name":"2323","type":2,"color":["#dad4ec","#f3e7e9"],"date":20180413,"days":94,"remark":"sdfs fsdfsdfs","sentence":{"id":"88c811e8b27c0738","content":"\u65f6\u95f4\u5e26\u7740\u660e\u663e\u7684\u6076\u610f \u7f13\u7f13\u5728\u6211\u7684\u8eab\u4e0a\u6d41\u901d","author":"\u65b0\u6d77\u8bda","book":"\u79d2\u901f\u4e94\u5398\u7c73"},"createTime":1531470637},{"id":"866a11e88749653d","name":"1212","type":1,"color":["#fc9e9a","#fed89c"],"date":20180713,"days":3,"remark":"2323","sentence":{"id":"88c811e8ab9d51f1","content":"\u8c22\u8c22\u4f60\u7684\u5fae\u7b11 \u66fe\u7ecf\u614c\u4e71\u8fc7\u6211\u7684\u5e74\u534e","author":"","book":""},"createTime":1531465472}]}}
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