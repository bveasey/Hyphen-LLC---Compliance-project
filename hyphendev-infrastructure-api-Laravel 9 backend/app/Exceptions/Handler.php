<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
        $this->renderable(function (QueryException $ex) {
            Log::error($ex->getMessage(), $ex->getTrace());

            DB::rollback();

            return $this->serverError();
        });

        $this->renderable(function (AccessDeniedHttpException | AuthorizationException $ex) {
            if ($ex->getStatusCode() === Response::HTTP_FORBIDDEN) {
                return $this->authorizationError($ex->getMessage());
            }
        });

        $this->renderable(function (NotFoundHttpException | NotFoundResourceException | ModelNotFoundException $ex) {
            if ($ex->getStatusCode() === Response::HTTP_NOT_FOUND) {
                return $this->error(Response::HTTP_NOT_FOUND, __('messages.resource-not-found'));
            }
        });

        $this->renderable(function (AuthenticationException $ex) {
            return response()->json(['message' => __('messages.unauthenticated')], Response::HTTP_UNAUTHORIZED);
        });

        $this->renderable(function (ValidationException $ex) {
            return $this->validationError($ex->errors());
        });

        $this->renderable(function (Exception | Throwable $ex) {
            Log::error($ex->getMessage(), $ex->getTrace());

            return $this->serverError();
        });
    }
}
