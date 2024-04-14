<?php

namespace App\Services;

use App\Entities\Task\Taskentitiy;
use App\Exceptions\DatabaseException;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class SaveNewTaskService
{
    public function save(Taskentitiy $task):void{
        try {
            $saved_data = Task::create($task->toArray());
        }catch (\Exception $e){
            DB::rollBack();
            throw new DatabaseException();
        }
        $task->setId($saved_data->id);
    }
}
