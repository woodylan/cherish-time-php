<?php

namespace App\Exceptions;

use App\Define\RetCode;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof EvaException) {
            return response(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }

        if ($e instanceof ApiInternalServerException) {
            return response(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }

        if ($e instanceof ThrottleException) {
            return response(['code' => $e->getCode(), 'msg' => $e->getMessage()], 429);
        }

        if (!env('APP_DEBUG', false)) {
            return response(['code' => RetCode::ERR_WRONG_SYSTEM_OPERATE, 'msg' => '系统异常，请稍后重试'], 500);
        }
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'test' || env('APP_ENV') == 'self') {
            return parent::render($request, $e);
        } else {
            return response(['code' => RetCode::ERR_WRONG_SYSTEM_OPERATE, 'msg' => '系统异常，请稍后重试'], 500);
        }
    }

    protected function shouldntReport(Exception $e)
    {
        if ($e instanceof EvaException) {
            return !$e->isReport();
        }
        return parent::shouldntReport($e);
    }
}
