<?php

namespace App\Logic;

use Illuminate\Support\Facades\Auth;

abstract class BaseLogic
{
    public function __construct($user = null)
    {
        $this->user = $user;
    }
}