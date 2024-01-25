<?php

namespace App\Services;

use App\Entities\UserEntity;
use App\Exceptions\CreateUserException;
use App\Models\User;

class CreateUserService
{
    public function save_new(UserEntity $user){
        User::create($user->to_array());
    }
}
