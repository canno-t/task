<?php

namespace App\Http\Controllers;

use App\Entities\Task\TaskEntity;
use App\Http\Requests\CreateTaskRequest;
use App\Responses\TaskResponse;
use App\Services\AssignUsersService;
use App\Services\SaveNewTaskService;
use Illuminate\Http\Request;

class CreateTaskController extends Controller
{

    public function __construct(
        private TaskEntity $taskEntity,
        private SaveNewTaskService $saveNewTaskService,
        private AssignUsersService $assignUsersService,
    )
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateTaskRequest $request)
    {
        $task = $this->taskEntity->fromArray($request->validated());
        if(
            $this->saveNewTaskService->save($task) &&
            $this->assignUsersService->assginToTask($task)
        ){
            return \response()->json(TaskResponse::setResponse(true, )->addParam('task_id', $task->getId())->returnResponse());
        }
        else{
            return \response()->json(TaskResponse::setResponse(false, 'cos nie dziala xd')->returnResponse());
        }

    }
}
