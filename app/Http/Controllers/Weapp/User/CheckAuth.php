<?php

namespace App\Http\Controllers\Weapp\User;

use App\Define\Common;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\Account;

class CheckAuth extends Controller
{
    public function run()
    {
        $auth = $this->input('auth');

        $logic = new Account();
        $ret = $logic->checkAuth($auth);

        return $this->render(Common::SUCCESS, 'success', $ret);
    }

    public static function rules()
    {
        return [
            'auth'          => ['required', 'auth'],
        ];
    }
}