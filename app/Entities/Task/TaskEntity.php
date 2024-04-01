<?php

namespace App\Entities\Task;

use App\Models\Task;

class TaskEntity
{
    public function fromArray(array $args){
        return '2' === $args['tasktype'] ? SubTaskEntity::fromArray($args) : MainTaskEntity::fromArray($args);
    }

    public function fromExistingTask(Task $task){
        if($task->task_type_id === '1'){
            return array_values($task->only('name', '$description', 'author_id', 'finish_date'));
//            return MainTaskEntity::fromArray()
        }
        return array_values($task->only('name', 'description', 'author_id', 'finish_date', 'dominant_task_id'));
    }

}
