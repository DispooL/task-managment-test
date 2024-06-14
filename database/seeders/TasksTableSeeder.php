<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the tasks table first to avoid duplicates
        Task::truncate();

        $tasks = [
            ['name' => 'Initial Task 1', 'priority' => 1],
            ['name' => 'Initial Task 2', 'priority' => 2],
            ['name' => 'Initial Task 3', 'priority' => 3],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
