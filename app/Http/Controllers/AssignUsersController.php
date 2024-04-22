<?php

namespace App\Http\Controllers;

use App\Entities\Task\TaskEntity;
use App\Http\Requests\AssignUsersRequest;
use App\Models\Task;
use App\Responses\TaskResponse;
use App\Services\AssignUsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignUsersController extends Controller
{

    public function __construct(
        private TaskEntity $taskEntity,
        private AssignUsersService $assignUsersService
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(AssignUsersRequest $request)
    {
        $validated = $request->validated();
        $task = Task::where('uuid', $validated['uuid'])->first();
        unset($validated['uuid']);
        DB::beginTransaction();
        $taskentity = $this->taskEntity->fromExistingTask($task);
        $taskentity->setUsers($validated['assigned_users']);
        if($request->user()->can('assignUsers', $task)) {
            $this->assignUsersService->assginToTask($taskentity);
            DB::commit();
            return response()->json(TaskResponse::setResponse('true', 'Users assigned successfully')->returnResponse());
        }
        return response()->json(TaskResponse::setResponse(false, 'You are not an author of this task')->returnResponse());
    }
}
