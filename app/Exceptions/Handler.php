<?php

namespace App\Exceptions;

use App\Http\Controllers\Web\WebController;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|RedirectResponse|Response
    {
        if ($request->wantsJson()) {
            return parent::prepareJsonResponse($request, $e);
        }

        if ($request->ajax()) {
            return parent::prepareJsonResponse($request, $e);
        }

        if ($e instanceof TokenMismatchException) {
            if ($request->is('*/panel/auth')) {
                return redirect()->route('panel.login', [])->withErrors([$e->getMessage()]);
            }
        }

        return parent::render($request, $e);
    }
}
