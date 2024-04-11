<?php

namespace App\Exceptions;

use App\Services\CreateUserService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        if($e instanceof HttpValidationFailException){
            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ], 200);
        }
        else if($e instanceof AuthorizationException){
            return response()->json([
                'status'=>'error',
                'message'=>'you are not logged in'
            ]);
        }
        else if($e instanceof DatabaseException){
            //TODO: log database errors
            return response()->json([
                'status'=>'error',
                'message'=>'Database error, please try again later'
            ]);
        }
        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }
}
