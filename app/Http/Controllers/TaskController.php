<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\CreateTaskDTO;
use App\DTO\ReorderTasksDTO;
use App\DTO\UpdateTaskDTO;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\ReorderTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Service\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskService->listTasks();

        return response()->json($tasks);
    }

    public function store(CreateTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $task = $this->taskService->createTask(new CreateTaskDTO($validated['name'], (int) $validated['priority']));

        return response()->json($task, 201);
    }

    public function update(UpdateTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $updatedTask = $this->taskService->updateTask(new UpdateTaskDTO((int) $validated['id'], $validated['name'], (int) $validated['priority']));

        return response()->json($updatedTask);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->taskService->deleteTask((int) $request->route('id'));

        return response()->json(null, 204);
    }

    public function reorder(ReorderTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->taskService->reorderTasks(new ReorderTasksDTO($validated['order']));

        return response()->json(['message' => 'Tasks reordered successfully']);
    }
}
