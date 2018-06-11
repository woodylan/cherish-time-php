<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\Common;
use App\Formatter\OrderFormatter;
use App\Http\Controllers\Controller;
use App\Logic\Order\TimeLogic;

class GetDetail extends Controller
{
    public function run()
    {
        $id = $this->input('id');

        $courseLogic = new TimeLogic($this->getUser());

        $ret = $courseLogic->getDetail($id, [
            new OrderFormatter(),
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