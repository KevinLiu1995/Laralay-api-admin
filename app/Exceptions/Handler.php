<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
		//  当请求为api时，自定义异常返回格式
		if ($request->expectsJson()) {
			if ($exception instanceof AuthenticationException) {
				if ($exception->getMessage() === 'Unauthenticated.') {
//                    $msg = '未登录或登录过期';
					$msg = '身份校验失败，请登录重试！';
				} else {
					$msg = $exception->getMessage();
				}
				return responseMessage($msg, 401, 401);
			}
			// 没有权限的异常处理
			if ($exception instanceof UnauthorizedException) {
				return responseMessage('没有权限', 403, 403);
			}
			// 429 请求过于频繁
			if ($exception instanceof TooManyRequestsHttpException){
				return responseMessage('请求过于频繁，请一分钟后重试', 429, 429);
			}
			if ($exception instanceof HttpException) {
				return responseMessage($exception->getMessage(), $exception->getStatusCode(), $exception->getStatusCode());
			}
			// 自定义验证失败的返回信息
			if ($exception instanceof ValidationException) {
				$errors = @$exception->validator->errors()->toArray();

//                $msg = [];
				$msg = '';
				foreach (array_values($errors) as $array_value) {
					foreach ($array_value as $item) {
//                        $msg[] = $item;
						$msg .= $item;
					}
				}
				return responseMessage($msg, 422, 422);
			}

		}
        return parent::render($request, $exception);
    }
}
