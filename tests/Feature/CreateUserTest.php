<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CreateUserTest extends TestCase
{

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    public function test(){
        $this->assertTrue(true);
    }

    public function test_createNewUser(){
//        DB::beginTransaction();
        $request_data = [
            'name'=>$this->faker->name,
            'password'=>$this->faker->password,
            'email'=>$this->faker->safeEmail
        ];

        $response = $this->post(route('user.create'),$request_data);

        $response->assertStatus(200)->assertJson([
            'message'=>'User '.$request_data['name'].' created successfully',
            'status'=>'success',
        ]);
//        DB::rollBack();
    }

    public function test_missing_parameters(){
        $request_data = [
            'name'=>$this->faker->name,
            'email'=>$this->faker->safeEmail
        ];
        $response = $this->post(route('user.create'),$request_data);

        $response->assertStatus(200)->assertJson([
            'message'=>'The password field is required.',
            'status'=>'error',
        ]);
    }


    public function test_invalid_parameter_type(){
        $request_data = [
            'name'=>$this->faker->name,
            'password'=>$this->faker->password,
            'email'=>$this->faker->name
        ];
        $response = $this->post(route('user.create'),$request_data);

        $response->assertStatus(200)->assertJson([
            'message'=>'The email field must be a valid email address.',
            'status'=>'error',
        ]);
    }

    public function test_not_unique_email_parameter(){
        $user = UserFactory::new()->create();
        $request_data = [
            'name'=>$this->faker->name,
            'password'=>$this->faker->password,
            'email'=>$user->email
        ];
        $response = $this->post(route('user.create'),$request_data);

        $response->assertStatus(200)->assertJson([
            'message'=>"Value '".$user->email."' is currently used, try something else",
            'status'=>'error',
        ]);
    }
//    public function __destruct(){
//        DB::rollBack();
//    }
}
