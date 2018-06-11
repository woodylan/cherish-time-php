<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\Common;
use App\Formatter\OrderFormatter;
use App\Http\Controllers\Controller;
use App\Logic\Order\TimeLogic;

class GetList extends Controller
{
    public function run()
    {
        $currentPage = $this->input('currentPage', 1);
        $perPage = $this->input('perPage', 20);
        $condition = $this->only(['perPage', 'currentPage']);

        $logic = new TimeLogic($this->getUser());
        $ret = $logic->getList($currentPage, $perPage, $condition, [
            new OrderFormatter(),
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