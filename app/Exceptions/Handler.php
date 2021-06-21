<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
    }

      //Excepciones 
      public function render($request, Throwable $e)
      {
          if ($e instanceof ModelNotFoundException) {
             return response()->json(['res'=> false, 'error' => 'Error de Modelo'], 400);
          }
          if ($e instanceof QueryException) {
             return response()->json(['res'=> false, 'message' => 'Error de consulta 800', $e->getMessage()], 500);
          }
          if($e instanceof HttpException){
              return response()->json(['res' => false, 'message' => 'Error de Ruta'], 404);
          }
          if($e instanceof AuthenticationException){
              return response()->json(['res' => false, 'message' => 'Error de de Autenticación'], 401);
          }
          if($e instanceof AuthorizationException){
              return response()->json(['res' => false, 'message' => 'Error de de Autorización, Usted no tiene permisos'], 403);
          }
          return parent::render($request, $e);
      }
}
