<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Assignments;

class DeleteTaskService
{
    public function delete(Task $task):bool{
        try{
            foreach (Assignments::where('task_id', $task['id'])->get() as $item) {
                $item->delete();
            }
            $task->delete();
        }catch (\Exception $e){
            return 0;
        }
        return 1;
    }
}
