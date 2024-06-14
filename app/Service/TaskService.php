<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreateTaskDTO;
use App\DTO\ReorderTasksDTO;
use App\DTO\UpdateTaskDTO;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    protected TaskRepository $taskRepository;

    public function __construct(TaskRepository $repository)
    {
        $this->taskRepository = $repository;
    }

    public function listTasks(): Collection
    {
        return $this->taskRepository->listTasks();
    }

    public function createTask(CreateTaskDTO $dto): Task
    {
        return $this->taskRepository->create($dto->name, $dto->priority);
    }

    public function updateTask(UpdateTaskDTO $dto): Task
    {
        return $this->taskRepository->update($dto->id, $dto->name, $dto->priority);
    }

    public function deleteTask(int $id): void
    {
        $this->taskRepository->delete($id);
    }

    public function reorderTasks(ReorderTasksDTO $dto): void
    {
        $this->taskRepository->reorder($dto->order);
    }
}
