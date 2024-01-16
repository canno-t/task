<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChildTaskRequest;
use Illuminate\Http\Request;

class CreateChildTaskController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ChildTaskRequest $request)
    {
        //
        dd($request->validated());
    }
}
