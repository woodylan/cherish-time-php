<?php

namespace App\Exceptions;

use Exception;

class EvaException extends Exception
{
    protected $isReport = false;

    public function isReport()
    {
        return $this->isReport;
    }
}
