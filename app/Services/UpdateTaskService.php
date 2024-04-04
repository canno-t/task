<?php

namespace App\Services;

use App\Models\Task;

class UpdateTaskService
{

    public function update(Task $task, array $data):bool{
        try {
            $task->update($data);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
