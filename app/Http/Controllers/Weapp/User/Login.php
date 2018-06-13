<?php

namespace App\Http\Controllers\Weapp\User;

use App\Define\Common;
use App\Http\Controllers\Controller;
use App\Logic\Weapp\Account;

class Login extends Controller
{
    public function run()
    {
        $code = $this->input('code');
        $iv = $this->input('iv');
        $encryptedData = $this->input('encryptedData');

        $logic = new Account();
        $ret = $logic->login($code, $iv, $encryptedData);

        return $this->render(Common::SUCCESS, 'success', $ret);
    }

    public static function rules()
    {
        return [
            'code'          => ['required', 'code'],
            'iv'            => ['required', 'iv'],
            'encryptedData' => ['required', 'encryptedData'],
        ];
    }
}