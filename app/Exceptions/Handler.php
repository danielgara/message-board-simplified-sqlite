<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Modify default Laravel exception rendering
        $this->renderable(function (NotFoundHttpException | MethodNotAllowedHttpException | ThrottleRequestsException $e, $request) {
            if ($request->is('api/*')) {
                $status = 404;

                if ($e instanceof ThrottleRequestsException) {
                    $status = 429;
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    $status = 405;
                }

                $message = $e->getMessage();

                if (empty($message)) {
                    $message = 'Not found';
                }

                return response()->json([
                    'message' => $message,
                ], $status);
            }
        });
    }

    protected function shouldReturnJson($request, Throwable $e)
    {
        return true;
    }
}
