<?php

namespace Tests\Feature;

use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{

    use WithFaker;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_NotCorrectTaskUuid():void{
        Sanctum::actingAs($this->user);

        $request = [
            'uuid'=>$this->faker->uuid
        ];

        $response = $this->post(route('task.delete', $request));

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The selected uuid is invalid.'
        ]);
    }

    public function test_NotAuthorOfTheTask():void{
        Sanctum::actingAs($this->user);

        $user = UserFactory::new()->create();

        $task = TaskFactory::new()->create([
            'author_id'=>$user['id'],
            'task_type_id'=>2
        ]);

        $request = [
            'uuid' => $task['uuid']
        ];

        $response = $this->post(route('task.delete', $request));

        $response->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'You are not the author of this task'
        ]);
    }

    public function test_SuccessfulDelete():void{
        Sanctum::actingAs($this->user);

        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>2
        ]);

        $request = [
            'uuid'=>$task['uuid']
        ];

        $response = $this->post(route('task.delete', $request));

        $response->assertOk()->assertJson([
            'status'=>'success',
            'message'=>'task deleted successfully'
        ]);
    }
}
