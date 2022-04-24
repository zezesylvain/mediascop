<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Session;

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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            Session::flush();
            return redirect("/login");
        }
        /**
        if ($exception instanceof AuthenticationException) {
        Session::flush();
        return redirect("/login");
        }
        if ($exception instanceof  QueryException){
        Session::flash('echec','Une erreur SQL survenue!');
        return back();
        }
        if ($exception instanceof \ErrorException){
        Session::flash('echec','Une erreur est survenue!');
        return back();
        }
        if ($exception instanceof \InvalidArgumentException){
        Session::flash('infos','La page que vous cherchez n\'existe pas !');
        return back();
        }
        // */
        return parent::render($request, $exception);
    }
}
