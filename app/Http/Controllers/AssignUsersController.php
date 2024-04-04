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
        $request = $request->validated();
        $task = Task::where('uuid', $request['uuid'])->first();
        unset($request['uuid']);
        DB::beginTransaction();
        $taskentity = $this->taskEntity->fromExistingTask($task);
        $taskentity->setUsers($request['assigned_users']);
        if ($this->assignUsersService->assginToTask($taskentity)) {
            DB::commit();
            return response()->json(TaskResponse::setResponse(true, 'users assigned successfully')->returnResponse());
        }
        return response()->json(TaskResponse::setResponse(false, 'unable to assign users, try again later')->returnResponse());
    }
}
