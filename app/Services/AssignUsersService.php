<?php

namespace App\Services;

use App\Entities\Task\Taskentitiy;
use App\Models\Assignments;
use Illuminate\Support\Facades\DB;

class AssignUsersService
{
    public function assginToTask(Taskentitiy $task){
        try {
            foreach ($task->getUsers() as $user) {
                Assignments::create([
                    'user_id'=>$user,
                    'task_id'=>$task->getId()
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }
}
