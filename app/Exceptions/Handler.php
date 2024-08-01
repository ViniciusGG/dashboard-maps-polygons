<?php

namespace App\Exceptions;

use App\Services\Api\ApiResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected ApiResponseService $apiResponse;

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $apiResponse = new ApiResponseService();

        if ($request->is('api/*')) {
            if ($e instanceof ValidationException) {
                $errors = $e->errors();
                $translatedErrors = [];

                foreach ($errors as $field => $fieldErrors) {
                    $translatedFieldErrors = [];

                    foreach ($fieldErrors as $fieldError) {
                        $translatedFieldErrors[] = trans($fieldError);
                    }

                    $translatedErrors[$field] = $translatedFieldErrors;
                }

                $content = [
                    'status' => false,
                    'message' => __('Validation Error'),
                    'error' => $translatedErrors
                ];

                return response()->json([
                    'headerCode' => 422,
                    'content' => $content
                ], 422);
                //return response()->json(['errors' => $translatedErrors], 422);
            }



            if ($e instanceof ModelNotFoundException) {
                $message = $e->getModel()::getModelLabel() . __('Not Found');
                return $apiResponse->errorResponse($message, 469, 404);
            }
            if ($e instanceof AuthenticationException) {
                // return response()->json([
                //     'status' => 'login-expired',
                //     'message' => __('Your session has expired. Please login again.')
                // ], 401);
                return $apiResponse->errorResponse(__('Your session has expired. Please login again.'), 401, 401);
            }

            $message = __('Unfortunately, an internal server error has occurred. Please try again later..');
            //$message = App::environment('local') ? $e->getMessage() : $message;
            $message = $e->getMessage();
            return $apiResponse->errorResponse($message, 500);
        }
        return parent::render($request, $e);
    }
}
