<?php

namespace App\Exceptions;

use Exception;

class ApiInternalServerException extends Exception
{
    protected $isReport = false;

    public function isReport()
    {
        return $this->isReport;
    }
}
