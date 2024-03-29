<?php

namespace Forum\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Forum\Exceptions\ThrottleException;

//use \ApiResponser;

class Handler extends ExceptionHandler
{
    //use ApiResponser;
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
        if ($exception instanceof ValidationException) {
            if ($request->expectsJson()) {
                //return $this->convertValidationExceptionToResponse($excetpion, $request);
                //do json convertion.
                $errors = $exception->validator->errors()->getMessages();

                return response()->json($errors, 422);
                //return $this->errorResponse($errors, 422);
                //$errors = collect($exception->getMessages());
                // $errors = $exception->validator->errors()->all();
                // $message ='';
                // foreach($errors as $error){
                //   $message .=$error.','
                // }
                //dd($errors);
                //return response($message, 422);
                //return response("Sorry, validation failed", 422);
                //ddreturn response("Sorry, validation failed ".$errors, 422);
            }
        }
        if ($exception instanceof ThrottleException) {
            return response('Sorry you  are posting too frequent', 429);
        }

        return parent::render($request, $exception);
    }
    // protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    // {
    //     $errors = $e->validator->errors()->getMessages();
    //     //return response()->json($errors, 422);
    //     return $this->errorResponse($errors, 422);
    // }
}
