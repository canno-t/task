<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name(),
            'description'=>$this->faker->sentence,
            'author_id'=>'',
            'task_type_id'=>'',
            'finish_date'=>$this->faker->dateTimeBetween('now', '+30years')->format('Y-m-d'),
            'dominant_task_id'=>null,
            'uuid'=>$this->faker->uuid
        ];
    }
}
