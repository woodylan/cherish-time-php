<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\TimeLogic;

class Edit extends Controller
{
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
            'name'   => ['required', '名称'],
            'type'   => ['required|integer|between:1,2', '类型'],
            'color'  => ['required', '颜色'],
            'date'   => ['required|integer', '日期'],
            'remark' => ['', '备注'],
        ];
    }
}