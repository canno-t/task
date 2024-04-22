<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UserApiTokenAuthTest extends TestCase
{

    use WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    public function test_successfull_login(){
        $passwd = $this->faker->name;
        $user = User::factory()->create([
            'password'=>$passwd
        ]);
        $request = [
            'email'=>  $user->email,
            'password'=>$passwd
        ];

        $response = $this->post(route('user.login'), $request);
        $response->assertStatus(200)->assertJson([
            'status'=>'success',
            'message'=>'user logged in',
        ])->assertJsonStructure([
            'token'
        ]);
    }

    public function test_if_sanctum_auth_works_properly(){
        $passwd = $this->faker->name;
        $user = User::factory()->create([
            'password'=>$passwd
        ]);
        $request = [
            'email'=>  $user->email,
            'password'=>$passwd
        ];

        $token = $this->post(route('user.login'), $request);
        $this->post(route('user.test'), [])->assertStatus(200);

    }
    public function test_non_existing_email(){
        $passwd = $this->faker->name;
        $user = User::factory()->create([
            'password'=>$passwd
        ]);
        $request = [
            'email'=>  'someemail@email.com',
            'password'=>$passwd
        ];

        $this->post(route('user.login'), $request)->assertStatus(200)
            ->assertJson([
                'status'=>'error',
                'message'=>'The selected email is invalid.'
        ]);
    }
    public function test_not_maching_credentials(){
        $passwd = $this->faker->name;
        $user = User::factory()->create([
            'password'=>$passwd
        ]);
        $request = [
            'email'=>  $user->email,
            'password'=>$passwd.'wrong'
        ];

        $this->post(route('user.login'), $request)
            ->assertStatus(200)
            ->assertJson([
                'status'=>'error',
                'message'=>'user credentials do not mach'
            ]);
    }

}
