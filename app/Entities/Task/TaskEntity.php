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
            $taskentity = MainTaskEntity::fromArray($task->only('name', '$description', 'author_id', 'finish_date'));
        }
        else {
            $taskentity = SubTaskEntity::fromArray($task->only('name', 'description', 'author_id', 'finish_date', 'dominant_task_id'));
        }
        $taskentity->setId($task->id);
        return $taskentity;
    }

}
