<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Responses\TaskResponse;
use App\Services\UpdateTaskService;
use Illuminate\Http\Request;

class UpdateTaskController extends Controller
{

    public function __construct(
        private UpdateTaskService $updateTaskService
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateTaskRequest $request)
    {
        $request = $request->validated();
        $task = Task::where('uuid', $request['uuid'])->first();
        unset($request['uuid']);
        return ($this->updateTaskService->update($task, $request)===true)
            ? response()->json(TaskResponse::setResponse(true, 'Task Updated Successfully')->returnResponse())
            : response()->json(TaskResponse::setResponse(false, 'Unable to Update Task')->returnResponse());

    }
}
