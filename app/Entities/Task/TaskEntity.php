<?php

namespace App\Entities\Task;

class TaskEntity
{
    public function fromArray(array $args){
        return '1' === $args['tasktype'] ? SubTaskEntity::fromArray($args) : MainTaskEntity::fromArray($args);
    }

}
