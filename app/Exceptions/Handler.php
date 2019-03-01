<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
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
            return self::render403($request, $exception);
        }else{
            return self::renderErrors($request, $exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     * 
     * UnauthorizedException 406 response
     */
    public function render403($request, Exception $exception)
    {
        if (($request->ajax() || $request->wantsJson()) && 'GET' != $request->method()) {
            $info = [
                'code' => '403',
                'info' => config('app.debug') ? $exception->getMessage() : '操作失败！',
                'data' => []
            ];
            return response()->json($info);
        } else {
            header('Location: /admin/403.extend');exit;
        }
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function renderErrors($request, Exception $exception)
    {
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

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request) {
            return response()->json(['status' => '401','msg' => 'token失效']);
        }
    }
}
