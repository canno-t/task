<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Models\Task;

class UpdateTaskService
{

    public function update(Task $task, array $data):void{
        try {
            $task->update($data);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
