<?php

namespace App\Http\Controllers;

use App\Entities\UserEntity;
use App\Exceptions\CreateUserException;
use App\Http\Requests\CreateUserRequest;
use App\Responses\UserResponse;
use App\Services\CreateUserService;
use http\Env\Response;
use Illuminate\Database\QueryException;

class CreteUserController extends Controller
{

    public function __construct(
        private CreateUserService $createUserService
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateUserRequest $request)
    {
        $user = UserEntity::from_array($request->validated());
        $message = null;
        $status = true;
        try {
            $this->createUserService->save_new($user);
        }catch (QueryException $e){
            if($e->errorInfo[1]==1062){
                $message = 'Value '.explode(' ',$e->errorInfo[2])[2].' is currently used, try something else';
                $status = false;
            }
        }catch (\Exception $e){
            $message = 'Operation currently unavailable';
            $status = false;
        }
        return \response()->json(UserResponse::setResponse($status,
            $message??'User '.$user->getName().' created successfully')
            ->returnResponse());
    }
}
