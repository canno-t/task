<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $user_id
 * @property $task_id
 *
 *
 * /Illuminate/Database/Eloquent/Builder
 */
class Assignments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id'
    ];

    public function getTask(){
        return $this->hasOne(Task::class, 'id', 'task_id');
    }
}
