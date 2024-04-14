<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Http\Middleware\Authenticate;

class CreateTaskTest extends TestCase
{

    use WithFaker;

    protected $user;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        DB::beginTransaction();
        $this->user = UserFactory::new()->create();
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_MissingRequestParameter(): void
    {
        $this->withoutMiddleware(Authenticate::class);
        $request = [
        'name'=>$this->faker->name,
        'description'=>$this->faker->sentence,
//            'finish_date'=>$this->faker->dateTimeBetween('now', '+30years'),//missing date
        'tasktype'=>2,
    ];
        $response = $this->post(route('task.create'), $request);

        $response->assertStatus(200)->assertJson([
            'status'=>'error',
            'message'=>'The finish date field is required.'
        ]);
    }

    public function test_IncorrectParameterType():void{
        Sanctum::actingAs($this->user);

        $request = [
            'name'=>$this->faker->name,
            'description'=>$this->faker->sentence,
            'finish_date'=>$this->faker->numberBetween(0, 1000),
            'tasktype'=>1,
        ];

        $this->post(route('task.create'), $request)->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The finish date field must be a valid date.'
        ]);
    }

    public function test_DateBeforeToday():void{
        Sanctum::actingAs($this->user);

        $request = [
            'name'=>$this->faker->name,
            'description'=>$this->faker->sentence,
            'finish_date'=>$this->faker->dateTimeBetween('-10days', '-1days')->format('Y-m-d'),
            'tasktype'=>1,
        ];

        $this->post(route('task.create'), $request)->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The finish date field must be a date after today.'
        ]);
    }

    public function test_NoDominantTaskWhileCreatingSubTask():void{
        Sanctum::actingAs($this->user);

        $request = [
            'name'=>$this->faker->name,
            'description'=>$this->faker->sentence,
            'finish_date'=>$this->faker->dateTimeBetween('now', '+30years')->format('Y-m-d'),
            'tasktype'=>2,
        ];

        $this->post(route('task.create'), $request)->assertOk()->assertJson([
            'status'=>'error',
            'message'=>'The dominant task id field is required when tasktype is 2.'
        ]);
    }

    public function test_SuccessfulMainTaskCreation():void{
        Sanctum::actingAs($this->user);

        $request = [
            'name'=>$this->faker->name,
            'description'=>$this->faker->sentence,
            'finish_date'=>$this->faker->dateTimeBetween('now', '+30years')->format('Y-m-d'),
            'tasktype'=>1,
        ];

        $this->post(route('task.create'), $request)->assertOk()->assertJsonStructure([
            'message',
            'status',
            'task_id'
        ])->assertJson([
            'status'=>'success',
            'message'=>'task created successfully'
        ]);
    }

    public function test_SuccessfulTaskCreationForSubTask():void{
        Sanctum::actingAs($this->user);

        $task = TaskFactory::new()->create([
            'author_id'=>$this->user['id'],
            'task_type_id'=>1
        ]);

        $request = [
            'name'=>$this->faker->name,
            'description'=>$this->faker->sentence,
            'finish_date'=>$this->faker->dateTimeBetween('now', '+30years')->format('Y-m-d'),
            'tasktype'=>2,
            'dominant_task_id'=>$task['id']
        ];

        $this->post(route('task.create'), $request)->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'task_id'
            ])
        ->assertJson([
            'status'=>'success',
            'message'=>'task created successfully'
        ]);
    }

    public function test_SuccessfulUserAssignments():void{
        Sanctum::actingAs($this->user);

        $users = collect(UserFactory::new()->count(3)->create())->map(function ($user){
            return $user->id;
        })->toArray();
        $request = [
            'name'=>$this->faker->name,
            'description'=>$this->faker->sentence,
            'finish_date'=>$this->faker->dateTimeBetween('now', '+30years')->format('Y-m-d'),
            'tasktype'=>1,
            'assigned_users'=>$users
        ];

        $response = $this->post(route('task.create'), $request)->assertOk()->assertJsonStructure([
            'message',
            'status',
            'task_id'
        ])->assertJson([
            'status'=>'success',
            'message'=>'task created successfully'
        ]);
    }
}
