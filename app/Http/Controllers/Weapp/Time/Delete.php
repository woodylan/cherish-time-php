<?php

namespace App\Http\Controllers\Weapp\Time;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Logic\Order\TimeLogic;

class Delete extends Controller
{
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