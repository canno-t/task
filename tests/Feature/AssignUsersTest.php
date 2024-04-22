<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AssignUsersService;
use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AssignUsersTest extends TestCase
{

    use WithFaker;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
        $this->user = UserFactory::new()->create();
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_SuccessfulAssignment():void{
        Sanctum::actingAs($this->user);

        $users = collect(UserFactory::new()->count(3)->create())->map(function($user){
            return $user->id;
        })->toArray();

        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>2
        ]);

        $params = [
            'uuid'=>$task['uuid']
        ];

        $request = [
            'assigned_users'=>$users
        ];

        $response = $this->post(route('task.assign', $params), $request);

        $response->assertOk()->assertJson([
            'status'=>'success',
            'message'=>'Users assigned successfully'
        ]);
    }

    public function test_nonExistingUsers():void{
        Sanctum::actingAs($this->user);
        $num = 3;
        $users = collect(UserFactory::new()->count($num)->create())->map(function($user){
            return $user->id;
        })->toArray();

        $users[$num] = 9999;
        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>2
        ]);

        $params = [
            'uuid'=>$task['uuid']
        ];

        $request = [
            'assigned_users'=>$users
        ];

        $response = $this->post(route('task.assign', $params), $request);

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'User'.$num.' does not exist'
        ]);
    }

    public function test_toManyUsers():void{
        Sanctum::actingAs($this->user);

        $users = collect(UserFactory::new()->count(15)->create())->map(function($user){
            return $user->id;
        })->toArray();

        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>2
        ]);

        $params = [
            'uuid'=>$task['uuid']
        ];

        $request = [
            'assigned_users'=>$users
        ];

        $response = $this->post(route('task.assign', $params), $request);

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The assigned users field must not have more than 10 items.'
        ]);
    }

    public function test_usersNotInArray():void{
        Sanctum::actingAs($this->user);

        $newuser = UserFactory::new()->create();

        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>2
        ]);

        $params = [
            'uuid'=>$task['uuid']
        ];

        $request = [
            'assigned_users'=>$newuser
        ];

        $response = $this->post(route('task.assign', $params), $request);

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The assigned users field must be an array.'
        ]);
    }

    public function test_notAnAuthorOfTheTask():void{
        Sanctum::actingAs($this->user);

        $users = collect(UserFactory::new()->count(3)->create())->map(function($user){
            return $user->id;
        })->toArray();

        $author = UserFactory::new()->create();

        $task = TaskFactory::new()->create([
            'author_id'=>$author['id'],
            'task_type_id'=>2
        ]);

        $params = [
            'uuid'=>$task['uuid']
        ];

        $request = [
            'assigned_users'=>$users
        ];

        $response = $this->post(route('task.assign', $params), $request);

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'You are not an author of this task'
        ]);
    }

    public function test_notExistingUuid():void{
        Sanctum::actingAs($this->user);

        $users = collect(UserFactory::new()->count(3)->create())->map(function($user){
            return $user->id;
        })->toArray();

        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>2
        ]);

        $params = [
            'uuid'=>$this->faker->uuid
        ];

        $request = [
            'assigned_users'=>$users
        ];

        $response = $this->post(route('task.assign', $params), $request);

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The selected uuid is invalid.'
        ]);
    }
}
