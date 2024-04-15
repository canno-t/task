<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTaskRequest;
use App\Responses\TaskResponse;
use App\Services\DeleteTaskService;
use App\Models\Task;

class DeleteTaskController extends Controller
{

    public function __construct(
        public DeleteTaskService $deleteTaskService
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(DeleteTaskRequest $request)
    {
        $task = Task::where('uuid', $request['uuid'])->first();
        if ($request->user()->cannot('delete', $task)) {
            return response()->json(TaskResponse::setResponse(false, 'You are not the author of this task')->returnResponse());
        }
        $this->deleteTaskService->delete($task);
        return response()->json(TaskResponse::setResponse(true, 'task deleted successfully')->returnResponse());
    }
}
