<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Order\TimeLogic;

class Create extends Controller
{
    public function run()
    {
        $inputData = $this->only(['name', 'type', 'color', 'date']);

        $logic = new TimeLogic($this->getUser());
        $logic->create($inputData);

        return $this->render(RetCode::SUCCESS, 'success');
    }

    public function rules()
    {
        return [
            'name'  => ['required', '名称'],
            'type'  => ['required|integer|between:1,2', '类型'],
            'color' => ['required', '颜色'],
            'date'  => ['required|integer', '日期'],
        ];
    }
}