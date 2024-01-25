<?php

namespace App\Entities;

use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserEntity
{
    private $password;
    public function __construct(
        private string $name,
        string $password,
        private string $email
    )
    {
        $this->password = Hash::make($password);
    }

    public static function from_array(array $user):UserEntity{
        return new self(
            $user['name'],
            $user['password'],
            $user['email'],
        );
    }

    public function to_array():array{
        return [
            'name'=>$this->name,
            'password'=>$this->password,
            'email'=>$this->email
        ];
    }
    public function getName(){
        return $this->name;
    }
}
