<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            if (($request->ajax() || $request->wantsJson()) && 'GET' != $request->method()) {
                $info = [
                    'code' => '500',
                    'info' => config('app.debug') ? $exception->getMessage() : '操作失败！',
                    'data' => []
                ];
                return response()->json($info);
            } else {
                header('Location: /admin/403');exit;
            }
        }
        if (($request->ajax() || $request->wantsJson()) && 'GET' != $request->method()) {
            $info = [
                'code' => '500',
                'info' => config('app.debug') ? $exception->getMessage() : '操作失败！',
                'data' => []
            ];
            return response()->json($info);
        } else {
            return parent::render($request, $exception);
        }
    }
}
