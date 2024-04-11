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
        $validated = $request->validated();
        $task = Task::where('uuid', $validated['uuid'])->first();
        unset($validated['uuid']);
        if ($request->user()->can('update', $task)) {
            $this->updateTaskService->update($task, $validated);
        }
        return response()->json(TaskResponse::setResponse(false, 'You are not the author of this task')->returnResponse());
    }
}
