<?php

namespace App\Services;

use App\Entities\Task\Taskentitiy;
use App\Exceptions\DatabaseException;
use App\Models\Assignments;
use Illuminate\Support\Facades\DB;

class AssignUsersService
{
    public function assginToTask(Taskentitiy $task):void{
        try {
            foreach ($task->getUsers() as $user) {
                $assignement = [
                    'user_id'=>$user,
                    'task_id'=>$task->getId()
                ];
                if(!(Assignments::where(collect($assignement)->map(function ($value, $key){
                    return [$value, '=', $key];
                })->toArray())->exists())){
                    Assignments::create($assignement);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new DatabaseException($e->getMessage());
        }
    }
}
