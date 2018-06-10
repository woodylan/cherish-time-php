<?php

namespace App\Logic\Admin;

use Illuminate\Support\Facades\Auth;

abstract class AdminBaseLogic
{
    public function __construct($user = null)
    {
        if (is_null($user)) {
            //没有运行在命令行时再获取
            if (!app()->runningInConsole()) {
                $this->user = Auth::adminUser();
            }
        } else {
            $this->user = $user;
        }
    }
}