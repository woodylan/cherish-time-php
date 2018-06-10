<?php

namespace App\Logic;

use Illuminate\Support\Facades\Auth;

abstract class BaseLogic
{
    public function __construct($user = null)
    {
        if (is_null($user)) {
            //没有运行在命令行时再获取
            if (!app()->runningInConsole()) {
                $this->user = Auth::user();
            }
        } else {
            $this->user = $user;
        }
    }
}