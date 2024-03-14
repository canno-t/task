<?php

namespace App\Services;

use App\Entities\Task\Taskentitiy;
use App\Models\Task;

class SaveNewTaskService
{
    public function save(Taskentitiy $task){
//        try {
            $saved_data = Task::create($task->toArray());
//        }catch (\Exception $e){
            //return false;
//        }
        $task->setId($saved_data->id);
        return true;
    }
}
