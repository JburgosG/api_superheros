<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    
    use ApiResponser;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        $response = $this->handleException($request, $exception);        
        //return parent::render($request, $exception);

        return $response;
    }

    public function handleException($request, Exception $exception)
    {
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof ModelNotFoundException){
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("There is not {$model} instance with the specified ID", 404);
        }

        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse("You do not have permission to execute this action.", 403);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse("The specified route was not found.", 404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse("The specified method is not valid.", 405);
        }

        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof QueryException){            
            if($exception->errorInfo[1] == 1451){
                return $this->errorResponse('You can not delete this resource because it is related to another.', 409);
            }
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        if(config('app.debug')){
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected failure, please try later.', 500);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isFrontend($request)){
            return redirect()->guest('login');
        }

        return $this->errorResponse('Unauthenticated', '401');
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors, 422) : redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);        
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}