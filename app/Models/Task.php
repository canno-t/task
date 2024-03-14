<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $authord_id
 * @property  $task_type_id
 * @property  $finish_date
 * @property  $task_priority_id
 * @property $dominant_task_id
 *
 *
 * @mixin /Illuminate/Database/Eloquent/Builder
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author_id',
        'task_type_id',
        'finish_date',
        'task_priority_id',
        'dominant_task_id'
    ];

    public function getTaskType(){
        return $this->hasOne(TaskTypes::class, 'id', 'task_type_id');
    }

    public function getTaskPriority(){
        return $this->hasOne(TaskPriopity::class, 'id', 'name');
    }
}
