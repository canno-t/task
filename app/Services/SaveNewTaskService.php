<?php

namespace App\Services;

use App\Entities\Task\Taskentitiy;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class SaveNewTaskService
{
    public function save(Taskentitiy $task){
        try {
            $saved_data = Task::create($task->toArray());
        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
        $task->setId($saved_data->id);
        return true;
    }
}
