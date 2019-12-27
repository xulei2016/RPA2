<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

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
        //如果路由中含有“api/”，则说明是一个 api 的接口请求
        if($request->is("api/*")){
            //如果错误是 ValidationException的一个实例，说明是一个验证的错误
            if($exception instanceof ValidationException){
                $result = [
                    "code"=>422,
                    //这里使用 $exception->errors() 得到验证的所有错误信息，是一个关联二维数组，所以
                    //使用了array_values()取得了数组中的值，而值也是一个数组，所以用的两个 [0][0]
                    "msg"=>array_values($exception->errors())[0][0]
                ];
                return response()->json($result);
            }
        }

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
            dd($exception->getMessage());
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
