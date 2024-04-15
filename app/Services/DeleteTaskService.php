<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Models\Task;
use App\Models\Assignments;

class DeleteTaskService
{
    public function delete(Task $task):void{
        try{
            foreach (Assignments::where('task_id', $task['id'])->get() as $item) {
                $item->delete();
            }
            $task->delete();
        }catch (\Exception $e){
            throw new DatabaseException();
        }
    }
}
