<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRepository
{
    public function create(string $name, int $priority): Task
    {
        return Task::create([
            'name' => $name,
            'priority' => $priority,
        ]);
    }

    public function update(int $id, string $name, int $priority): Task
    {
        $task = Task::findOrFail($id);
        $task->update([
            'name' => $name,
            'priority' => $priority
        ]);

        return $task;
    }

    public function delete(int $id): void
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }

    public function reorder(array $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order as $item) {
                $taskId = $item['id']; // Extract the task ID
                $taskPriority = $item['priority']; // Extract the new priority

                $task = Task::find($taskId);
                if ($task) {
                    $task->priority = $taskPriority;
                    $task->save();
                }
            }
        });
    }

    public function listTasks()
    {
        return Task::orderBy('priority')->get();
    }
}
