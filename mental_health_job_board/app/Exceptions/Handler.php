<?php

namespace App\Exceptions;

use App\Mail\ErrorReport;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;
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
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $e)
    {
        if ($this->shouldReport($e) && app()->isProduction()) {
            Mail::to('vladimir@greenice.net')->send(new ErrorReport($e));
            Mail::to('kostiantyn.shapoval@greenice.net')->send(new ErrorReport($e));
            Mail::to('romanmurashkin@greenice.net')->send(new ErrorReport($e));
        }

        parent::report($e);
    }
}
